#!/usr/bin/env python3
"""
Cleaner for casa-novara.sql

Features:
 - Default: create a safe backup and write a copy with all INSERT ...; blocks removed.
 - --remove-truncate: also remove TRUNCATE TABLE statements.
 - --schema-only: produce a schema-only SQL that keeps CREATE/ALTER/DROP/USE statements and removes comments, pragmas, SETs, START/COMMIT/TRANSACTION, TRUNCATE and INSERT statements.

The script writes an output filename depending on the selected mode so previous runs aren't overwritten.
"""
import shutil
import re
import os
import argparse
import datetime

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_DIR = os.path.join(ROOT, 'DB')
INPUT = os.path.join(DB_DIR, 'casa-novara.sql')

def make_backup(path: str) -> str:
    """Make a non-destructive backup (don't overwrite existing .bak). Returns backup path."""
    base = path + '.bak'
    if not os.path.exists(base):
        shutil.copy2(path, base)
        return base
    # find a new name with timestamp
    ts = datetime.datetime.now().strftime('%Y%m%d%H%M%S')
    alt = f"{path}.bak.{ts}"
    shutil.copy2(path, alt)
    return alt

def default_output_path(remove_truncate: bool, schema_only: bool) -> str:
    if schema_only:
        return os.path.join(DB_DIR, 'casa-novara.schema.sql')
    if remove_truncate:
        return os.path.join(DB_DIR, 'casa-novara.no-inserts-no-truncate.sql')
    return os.path.join(DB_DIR, 'casa-novara.no-inserts.sql')


def main():
    parser = argparse.ArgumentParser(description='Clean casa-novara SQL dump')
    parser.add_argument('--remove-truncate', action='store_true', help='Also remove TRUNCATE TABLE statements')
    parser.add_argument('--schema-only', action='store_true', help='Produce schema-only SQL (keep CREATE/ALTER/DROP/USE) and strip comments/pragmas/sets)')
    parser.add_argument('--input', default=INPUT, help='Path to input SQL file')
    parser.add_argument('--output', default=None, help='Path to write output file (overrides auto name)')
    args = parser.parse_args()

    input_path = args.input
    if not os.path.exists(input_path):
        print(f"Input file not found: {input_path}")
        raise SystemExit(1)

    backup_path = make_backup(input_path)

    output_path = args.output or default_output_path(args.remove_truncate, args.schema_only)

    insert_re = re.compile(r'^\s*INSERT\s+INTO\b', re.IGNORECASE)
    truncate_re = re.compile(r'^\s*TRUNCATE\s+TABLE\b', re.IGNORECASE)
    create_re = re.compile(r'^\s*(CREATE|ALTER|DROP)\b', re.IGNORECASE)
    use_re = re.compile(r'^\s*USE\b', re.IGNORECASE)

    blocks_removed = 0
    lines_kept = 0
    lines_removed = 0

    with open(input_path, 'r', encoding='utf-8', errors='replace') as fin, open(output_path, 'w', encoding='utf-8') as fout:
        if args.schema_only:
            # Write only DDL statements: CREATE/ALTER/DROP and USE and CREATE DATABASE
            in_block = False
            block_write = False
            for line in fin:
                s = line.lstrip()
                # skip plain comments and pragmas and set/transaction lines
                if s.startswith('--') or s.startswith('/*') or s.startswith('*/') or s.startswith('/*!'):
                    continue
                lowered = s.upper()
                if lowered.startswith('SET ') or lowered.startswith('START TRANSACTION') or lowered.startswith('COMMIT') or lowered.startswith('LOCK ') or lowered.startswith('UNLOCK '):
                    continue
                if insert_re.match(line) or truncate_re.match(line):
                    # skip data statements
                    # count removed lines for visibility
                    lines_removed += 1
                    continue
                # check for statements we want to keep
                if create_re.match(line) or use_re.match(line) or re.match(r'^\s*CREATE\s+DATABASE\b', line, re.IGNORECASE):
                    # write this line and continue until semicolon
                    fout.write(line)
                    lines_kept += 1
                    if line.rstrip().endswith(';'):
                        in_block = False
                        block_write = False
                    else:
                        in_block = True
                        block_write = True
                    continue

                if in_block and block_write:
                    fout.write(line)
                    lines_kept += 1
                    if line.rstrip().endswith(';'):
                        in_block = False
                        block_write = False
                    continue

                # everything else is ignored in schema-only mode
                lines_removed += 1

        else:
            skip = False
            skipping_block_type = None
            for line in fin:
                if not skip:
                    if insert_re.match(line):
                        skip = True
                        skipping_block_type = 'INSERT'
                        blocks_removed += 1
                        lines_removed += 1
                        if line.rstrip().endswith(';'):
                            skip = False
                            skipping_block_type = None
                        continue
                    if args.remove_truncate and truncate_re.match(line):
                        # drop this line
                        lines_removed += 1
                        blocks_removed += 1
                        continue
                    # otherwise keep
                    fout.write(line)
                    lines_kept += 1
                else:
                    # currently skipping an INSERT block
                    lines_removed += 1
                    if line.rstrip().endswith(';'):
                        skip = False
                        skipping_block_type = None

    print(f"Backup saved to: {backup_path}")
    print(f"Cleaned file written to: {output_path}")
    print(f"INSERT/TRUNCATE blocks removed (approx): {blocks_removed}")
    print(f"Lines kept: {lines_kept}, lines removed: {lines_removed}")


if __name__ == '__main__':
    main()
