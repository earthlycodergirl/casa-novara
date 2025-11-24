<?php
require 'base.php';

$page_type = 4;

$show_alert = 0;
$note_head = '';
$note_txt = '';

$edit = 0;
$blog_id = 0;
$blog_title = '';
$blog_content = '';
$blog_author = '';
$blog_img = '';
$blog_lang = '';


if(isset($_GET['bid']) && $_GET['bid'] > 0){
    $getEntries = new SqlIt("SELECT * FROM site_blog WHERE blog_id = ?","select",array($_GET['bid']));
    if($getEntries->NumResults > 0){
        $rr = $getEntries->Response[0];
        $blog_id = $rr->blog_id;
        $blog_title = $rr->title;
        $blog_content = $rr->content;
        $blog_author = $rr->author;
        $blog_img = $rr->main_img;
        $blog_lang = $rr->lang;
        $edit = 1;
    }
}

if(isset($_POST['update_blog'])){


    $blog_img = '';

    if(isset($_FILES['main_img']) && $_FILES['main_img']['name'] != ''){
      include 'assets/inc/process/img_up_function.php';
      $upload_dir = 'uploads/blog/main/';
      $folder_dir = 'main/';
      $return = uploadImg($upload_dir,'main_img');
      if($return['success'] == 1){
        $blog_img = $return['filename'];
      } else {
        // Handle upload errors
        $show_alert = 'danger';
        $note_head = 'Upload Error';
        $note_txt = $return['errors'];
      }
    }


    if(isset($_POST['blog_id']) && $_POST['blog_id'] > 0){

        if($blog_img == '' && $_POST['bimg_orig'] != ''){
          $blog_img = $_POST['bimg_orig'];
        }
        // Edit the article
        new Sqlit("UPDATE site_blog SET title=?,content=?,author=?,main_img=?,lang=? WHERE blog_id=?","update",array($_POST['title'],$_POST['content'],$_POST['author'],$blog_img,$_POST['lang'],$_POST['blog_id']));

        $blog_id = $_POST['blog_id'];

        $note_txt = 'The article was updated successfully.';
    }else{
        // Add article
        new SqlIt("INSERT INTO site_blog (title,content,author,posted,main_img,lang) VALUES (?,?,?,NOW(),?,?)","insert",array($_POST['title'],$_POST['content'],$_POST['author'],$blog_img,$_POST['lang']));

        $getArt = new SqlIt("SELECT blog_id FROM site_blog ORDER BY blog_id DESC LIMIT 1","select",array());
        $blog_id = $getArt->Response[0]->blog_id;

        $note_txt = 'The article was added successfully.';
    }

    $show_alert = 'success';
    $note_head = 'Article saved';

    $getEntries = new SqlIt("SELECT * FROM site_blog WHERE blog_id = ?","select",array($blog_id));
    if($getEntries->NumResults > 0){
        $rr = $getEntries->Response[0];
        $blog_id = $rr->blog_id;
        $blog_title = $rr->title;
        $blog_content = $rr->content;
        $blog_author = $rr->author;
        $blog_img = $rr->main_img;
        $blog_lang = $rr->lang;
        $edit = 1;
    }
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $site_title ?></title>
    <meta name="robots" content="noindex" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i" rel="stylesheet">

    <!-- Styles -->
    <link href="assets/css/core.min.css" rel="stylesheet">
    <link href="assets/css/app.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
    <link rel="icon" href="assets/img/favicon.png">
  </head>

  <body data-alert="<?= $show_alert ?>" data-txt="<?= $note_txt ?>" data-head="<?= $note_head ?>">

    <?php include 'assets/inc/header.php'; ?>

    <header class="topbar">
      <div class="topbar-left">
        <button class="topbar-btn sidebar-toggler">☰</button>
        <a class="topbar-btn fs-16" href="#" title="" data-provide="fullscreen tooltip" data-placement="bottom" data-original-title="Toggle fullscreen">
          <i class="ion-android-expand fullscreen-default"></i>
          <i class="ion-android-contract fullscreen-active"></i>
        </a>
      </div>

      <div class="topbar-right">
        <div>
          <a class="btn btn-outline btn-default" href="blog-list.php"><i class="ti-arrow-left"></i> Return to blog list</a>
          <a class="btn btn-outline btn-info" href="blog-entry.php"><i class="ti-plus"></i> Add a new blog entry</a>
        </div>
      </div>
    </header>

    <!-- Main container -->
    <main class="main-container">
      <div class="main-content">


        <h1 class="header-title">
            <strong><?php echo ($edit == 1) ? 'Update Article' : 'Add New Article'; ?></strong>
            <small class="pt-0 mb-4">Enter an article here, select the language you are posting in (English or Spanish).</small>
        </h1>
         <form action="blog-entry.php" method="post" enctype="multipart/form-data">
        <div class="card shadow-3">
            <div class="card-body">
                <?php if($edit == 1){ ?>
                <input type="hidden" name="blog_id" value="<?= $blog_id ?>">
                <?php } ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Article title</label>
                            <input type="text" name="title" class="form-control" value="<?= $blog_title ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Author</label>
                            <input type="text" name="author" class="form-control" value="<?= $blog_author ?>" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Language</label>
                            <select name="lang" class="form-control" required>
                              <option selected value="en"> - select - </option>
                              <option value="en" <?php if($blog_lang == 'en'){ echo 'selected'; } ?>>English</option>
                              <option value="es" <?php if($blog_lang == 'es'){ echo 'selected'; } ?>>Español</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Article content</label>
                            <textarea name="content" class="form-control" rows="8" id="article_content" data-provide="summernote" data-toolbar="full" required><?= $blog_content ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="blog-main-img">
                  <div class="row">
                    <div class="col-sm-3">
                      <input name="bimg_orig" type="hidden" value="<?= $blog_img ?>" />
                      <?php if($blog_img != ''){ ?>

                        <img src="uploads/blog/main/<?= $blog_img ?>" class="blog-img">

                      <?php }else{ ?>

                      <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjUwIiBoZWlnaHQ9IjE1MCIgdmlld0JveD0iMCAwIDI1MCAxNTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyNTAiIGhlaWdodD0iMTUwIiBmaWxsPSIjRjVGNUY1Ii8+CjxyZWN0IHg9IjEiIHk9IjEiIHdpZHRoPSIyNDgiIGhlaWdodD0iMTQ4IiBzdHJva2U9IiNEREREREQiIHN0cm9rZS13aWR0aD0iMiIgZmlsbD0ibm9uZSIvPgo8Y2lyY2xlIGN4PSI4NSIgY3k9IjU1IiByPSIxNSIgZmlsbD0iI0NDQ0NDQyIvPgo8cGF0aCBkPSJNNzAgODBMMTAwIDUwTDE4MCA5MEwyMzAgNDBWMTMwSDIwVjgwWiIgZmlsbD0iI0RERERERCIvPgo8dGV4dCB4PSIxMjUiIHk9IjkwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTQiIGZpbGw9IiM5OTk5OTkiPkJsb2cgSW1hZ2U8L3RleHQ+Cjx0ZXh0IHg9IjEyNSIgeT0iMTA4IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBmb250LWZhbWlseT0iQXJpYWwsIHNhbnMtc2VyaWYiIGZvbnQtc2l6ZT0iMTIiIGZpbGw9IiNCQkJCQkIiPjI1MHgxNTA8L3RleHQ+Cjwvc3ZnPgo=" alt="Blog Image Placeholder" class="img-fluid" style="border: 1px solid #ddd; border-radius: 4px;" />
                      <?php } ?>
                    </div>
                    <div class="col-sm-9">
                      <div class="form-group file-wrap">
                        <label for="main_img">Main Image</label>
                        <p>This image will be visible on the blog list as well as on search results in google.</p>
                        <input type="file" name="main_img" />

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
          <div class="text-right">
            <?php if($edit == 1){ ?>
            <a href="../blog-page.php?bid=<?= $blog_id ?>" target="_blank" class="btn btn-outline btn-info btn-lg mr-2"><i class="ti-eye"></i> Preview Article</a>
            <?php } ?>
            <button type="submit" name="update_blog" class="btn btn-primary btn-lg">
              <i class="ti-<?php echo ($edit == 1) ? 'save' : 'plus'; ?>"></i> 
              <?php echo ($edit == 1) ? 'Save Changes' : 'Add Article'; ?>
            </button>
          </div>
          <div class="clearfix"></div>
        </form>

      </div><!--/.main-content -->


     <?php include 'assets/inc/footer.php'; ?>

    </main>
    <!-- END Main container -->


    <!-- DELETE MODAL -->
    <div class="modal modal-top fade" id="delete_entry" tabindex="-1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm article removal</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <form action="blog-list.php" method="get">
              <input type="hidden" name="dd" value="0" id="dd">
          <div class="modal-body">
              <p><b>Are you sure you would like to permanently delete this blog entry?</b> You will not be able to recover it once this is done. Please confirm your decision.</p>

          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-bold btn-outline btn-secondary" data-dismiss="modal">Cancel removal</button>
            <button type="submit" class="btn btn-bold btn-outline btn-danger">Confirm removal</button>
          </div>
            </form>
        </div>
      </div>
    </div>


    <!-- Scripts -->
    <script src="assets/js/core.min.js" data-provide="sweetalert"></script>
    <script src="assets/js/app.min.js"></script>
    <script src="assets/js/script.min.js"></script>
    <script src="assets/js/listings.js"></script>

  </body>
</html>
