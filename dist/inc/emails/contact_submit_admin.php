<?php
require_once('../../../adm/classes/sql.class.php');

/*$_POST['name'] = 'Ashley Simmons';
$_POST['email'] = 'myemail@you.com';
$_POST['message'] = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';*/
$errors = array();
$success = 0;
if(isset($_POST['email'])){

if(isset($_POST['name']) && strlen($_POST['name']) > 3){
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}




	// first filter the input elements
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		$email = test_input($_POST['email']);
	}else{
		$errors['email'] = 'Invalid email';
	}

	$name = test_input($_POST['name']);

	if(strlen($_POST['message']) < 20){
		$errors['message'] = 'Please enter a message with minimum of 20 characters';
	}else{
		$message = test_input($_POST['message']);
	}

if(empty($errors)){
$send_to = array();
// get administrative emails to send to
$getCon = new SqlIt("SELECT * FROM site_contact WHERE contact_section = ?","select",array('contact_emails'));
if($getCon->NumResults > 0){

	foreach($getCon->Response as $cc){
		$send_to[] = $cc->contact_value;
	}

	function removeWhitespace($buffer){
	    return preg_replace('/\s+/', ' ', $buffer);
	}

	//$to = 'info@kiinrealty.com';
	$subject = 'Contact Received - MIA Realty';
	$from = 'MIA Realty <no-reply@kiinrealty.com>';

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Create email headers
	$headers .= 'From: '.$from."\r\n".
	    'Reply-To: '.$from."\r\n" .
	    'X-Mailer: PHP/' . phpversion();

	ob_start('removeWhitespace');


		$email = '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		<!--[if gte mso 9]>
		<xml>
			<o:OfficeDocumentSettings>
			<o:AllowPNG/>
			<o:PixelsPerInch>96</o:PixelsPerInch>
			</o:OfficeDocumentSettings>
		</xml>
		<![endif]-->
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="format-detection" content="date=no" />
		<meta name="format-detection" content="address=no" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="x-apple-disable-message-reformatting" />
		<!--[if !mso]><!-->
		<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&display=swap" rel="stylesheet" />
		<!--<![endif]-->
		<title>Contact form submission from MIA Realty website.</title>
		<!--[if gte mso 9]>
		<style type="text/css" media="all">
			sup { font-size: 100% !important; }
		</style>
		<![endif]-->
		<!-- body, html, table, thead, tbody, tr, td, div, a, span { font-family: Arial, sans-serif !important; } -->


		<style type="text/css" media="screen">
			body {padding:0 !important;margin:0 auto !important;display:block !important;min-width:100% !important;width:100% !important;background: #e6e6e6;-webkit-text-size-adjust:none;}
			a { color:#92C01F; text-decoration:none }
			p { padding:0 !important; margin:0 !important }
			img { margin: 0 !important; -ms-interpolation-mode: bicubic; /* Allow smoother rendering of resized image in Internet Explorer */ }

			a[x-apple-data-detectors] { color: inherit !important; text-decoration: inherit !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; }

			.btn-16 a { display: block; padding: 15px 35px; text-decoration: none; }
			.btn-20 a { display: block; padding: 15px 35px; text-decoration: none; }

			.l-white a { color: #ffffff; }
			.l-black a { color: #282828; }
			.l-pink a { color: #92C01F; }
			.l-grey a { color: #6e6e6e; }
			.l-purple a { color: #FFCB63; }

			.gradient { background: linear-gradient(to right, #999999 0%,#000000 100%); }

			.btn-secondary { border-radius: 10px; background: linear-gradient(to right, #999999 0%,#000000 100%); }


			/* Mobile styles */
			@media only screen and (max-device-width: 480px), only screen and (max-width: 480px) {
				.mpx-10 { padding-left: 10px !important; padding-right: 10px !important; }

				.mpx-15 { padding-left: 15px !important; padding-right: 15px !important; }

				.mpb-100 { padding-bottom: 100px !important; }

				u + .body .gwfw { width:100% !important; width:100vw !important; }

				.td,
				.m-shell { width: 100% !important; min-width: 100% !important; }

				.mt-left { text-align: left !important; }
				.mt-center { text-align: center !important; }
				.mt-right { text-align: right !important; }

				.me-left { margin-right: auto !important; }
				.me-center { margin: 0 auto !important; }
				.me-right { margin-left: auto !important; }

				.mh-auto { height: auto !important; }
				.mw-auto { width: auto !important; }

				.fluid-img img { width: 100% !important; max-width: 100% !important; height: auto !important; }

				.column,
				.column-top,
				.column-dir-top { float: left !important; width: 100% !important; display: block !important; }

				.m-hide { display: none !important; width: 0 !important; height: 0 !important; font-size: 0 !important; line-height: 0 !important; min-height: 0 !important; }
				.m-block { display: block !important; }

				.mw-15 { width: 15px !important; }

				.mw-2p { width: 2% !important; }
				.mw-32p { width: 32% !important; }
				.mw-49p { width: 49% !important; }
				.mw-50p { width: 50% !important; }
				.mw-100p { width: 100% !important; }

				.mmt-0 { margin-top: 0 !important; }
			}

				</style>
	</head>
	<body class="body" style="padding:0 !important; margin:0 auto !important; display:block !important; min-width:100% !important; width:100% !important; background:#f4f4f4; -webkit-text-size-adjust:none;">
		<center>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 0; padding: 0; width: 100%; height: 100%;" bgcolor="#f4f4f4" class="gwfw">
				<tr>
					<td style="margin: 0; padding: 0; width: 100%; height: 100%;" align="center" valign="top">
						<table width="600" border="0" cellspacing="0" cellpadding="0" class="m-shell">
							<tr>
								<td class="td" style="width:600px; min-width:600px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="mpx-10">

												<!-- Container -->
												<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td class="gradient pt-10" style="border-radius: 10px 10px 0 0; padding-top: 10px;" bgcolor="#000">
															<table width="100%" border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td style="border-radius: 10px 10px 0 0;" bgcolor="#ffffff">
																		<!-- Logo -->
																		<table width="100%" border="0" cellspacing="0" cellpadding="0">
																			<tr>
																				<td class="img-center p-30 px-15" style="font-size:0pt; line-height:0pt; text-align:center; padding: 30px; padding-left: 15px; padding-right: 15px;">
																					<a href="#" target="_blank"><img src="https://kiinrealty.com/dist/inc/emails/images/logo.png" width="112" height="25" border="0" alt="" /></a>
																				</td>
																			</tr>
																		</table>
																		<!-- Logo -->

																		<!-- Main -->
																		<table width="100%" border="0" cellspacing="0" cellpadding="0">
																			<tr>
																				<td class="px-50 mpx-15" style="padding-left: 50px; padding-right: 50px;">
																					<!-- Section - Intro -->
																					<table width="100%" border="0" cellspacing="0" cellpadding="0">
																						<tr>
																							<td class="pb-50" style="padding-bottom: 50px;">
																								<table width="100%" border="0" cellspacing="0" cellpadding="0">
																									<tr>
																										<td class="title-36 a-center pb-15" style="font-size:26px; line-height:34px; color:#282828; font-family:\'PT Sans\', Arial, sans-serif; min-width:auto !important; text-align:center; padding-bottom: 15px;">
																											Contact submission from website
																										</td>
																									</tr>
																									<tr>
																										<td class="text-16 lh-26 a-center" style="font-size:14px; color:#6e6e6e; font-family:\'PT Sans\', Arial, sans-serif; min-width:auto !important; line-height: 26px; text-align:center;">
																											You have received a contact submission from your website. The client information is below. Please do not reply to this email directly.
																										</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</table>
																					<!-- END Section - Intro -->


																					<!-- Section - Contact -->
																					<table width="100%" border="0" style="border: 1px solid #eeeeee;" cellspacing="0" cellpadding="0">

																						<tr>
																							<td class="text-16 lh-26 a-center" width="120" style="font-size:14px; color:#6e6e6e; font-family:\'PT Sans\', Arial, sans-serif; min-width: 140px; !important; line-height: 26px; text-align:center; padding: 5px; border-bottom: 1px solid #ddd;">
																								Name
																							</td>
																							<td class="text-16 lh-26 a-center" style="font-size:14px; color:#333333; font-family:\'PT Sans\', Arial, sans-serif; line-height: 26px; text-align:left; padding: 5px; border-bottom: 1px solid #ddd;">'.$name.'</td>
																						</tr>


																						<tr>
																							<td class="text-16 lh-26 a-center" width="120" style="font-size:14px; color:#6e6e6e; font-family:\'PT Sans\', Arial, sans-serif; min-width: 140px !important; line-height: 26px; text-align:center; padding: 5px; border-bottom: 1px solid #ddd;">
																								Email
																							</td>
																							<td class="text-16 lh-26 a-center" style="font-size:14px; color:#333333; font-family:\'PT Sans\', Arial, sans-serif; line-height: 26px; text-align:left; padding: 5px; border-bottom: 1px solid #ddd;"><a href="mailto:'.$email.'">'.$email.'</a></td>
																						</tr>


																						<tr>
																							<td class="text-16 lh-26 a-center" width="120" vertical-align="top" style="font-size:14px; color:#6e6e6e; font-family:\'PT Sans\', Arial, sans-serif; min-width: 140px !important; line-height: 26px; text-align:center; padding: 5px;">
																								Notes
																							</td>
																							<td class="text-16 lh-26 a-center" style="font-size:14px; color:#333333; font-family:\'PT Sans\', Arial, sans-serif; line-height: 26px; text-align:left; padding: 5px;">'.$message.'</td>
																						</tr>


																					</table>
																					<!-- END Section - Contact -->


																				</td>
																			</tr>
																			<tr>
																				<td style="height: 50px; background: none"></td>
																			</tr>
																		</table>
																		<!-- END Main -->
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
												<!-- END Container -->

												<!-- Footer -->
												<table width="100%" border="0" cellspacing="0" cellpadding="0">

														<tr>
															<td class="p-50 mpx-15" bgcolor="#222222" style="border-radius: 0 0 10px 10px; padding: 50px;">
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td align="center" class="pb-20" style="padding-bottom: 20px;">
																			<!-- Socials -->
																			<table border="0" cellspacing="0" cellpadding="0">
																				<tr>
																					<td class="img" width="34" style="font-size:0pt; line-height:0pt; text-align:left;">
																						<a href="https://facebook.com/kiinrealty.com" target="_blank"><img src="https://kiinrealty.com/dist/inc/emails/images/ico_facebook.png" width="34" height="34" border="0" alt="" /></a>
																					</td>
																					<td class="img" width="15" style="font-size:0pt; line-height:0pt; text-align:left;"></td>
																					<td class="img" width="34" style="font-size:0pt; line-height:0pt; text-align:left;">
																						<a href="https://instagram.com/kiinrealty.com" target="_blank"><img src="https://kiinrealty.com/dist/inc/emails/images/ico_instagram.png" width="34" height="34" border="0" alt="" /></a>
																					</td>
																				</tr>
																			</table>
																			<!-- END Socials -->
																		</td>
																	</tr>
																	<tr>
																		<td class="text-14 lh-24 a-center c-white l-white pb-20" style="font-size:14px; font-family:\'PT Sans\', Arial, sans-serif; min-width:auto !important; line-height: 24px; text-align:center; color:#ffffff; padding-bottom: 20px;">
																			MIA REALTY - MEXICO
																			<br />
																			<a href="tel:+529841310957" target="_blank" class="link c-white" style="text-decoration:none; color:#ffffff;"><span class="link c-white" style="text-decoration:none; color:#ffffff;">+52 984 131 0957</span></a>
																			<br />
																			<a href="mailto:info@kiinrealty.com" target="_blank" class="link c-white" style="text-decoration:none; color:#ffffff;"><span class="link c-white" style="text-decoration:none; color:#ffffff;">info@kiinrealty.com</span></a> - <a href="https://kiinrealty.com" target="_blank" class="link c-white" style="text-decoration:none; color:#ffffff;"><span class="link c-white" style="text-decoration:none; color:#ffffff;">kiinrealty.com</span></a>
																		</td>
																	</tr>

																			</table>
																			<!-- END Download App -->
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>											<!-- END Footer -->
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</center>
	</body>
	</html>';

	//echo $email;
	ob_get_flush();

	if(!empty($send_to) && empty($errors)){
		foreach($send_to as $to){
			// Sending email
			if(mail($to, $subject, $email, $headers)){
			    $success = 1;
			} else{
					$success = 0;
			    $errors['general'] = 'Unable to send email. Please try again.';
			}
		}

	}

	}
	}

	}else{
		$errors['name'] = 'Please enter a valid name';
	}
	echo json_encode(array('errors'=>$errors,'success'=>$success));
}
	?>

