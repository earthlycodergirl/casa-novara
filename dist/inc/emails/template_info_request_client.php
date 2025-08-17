<?php
require_once('../../../adm/classes/sql.class.php');
require_once('../../classes/publist.class.php');

$_GET['prop'] = 62;

if(isset($_GET['prop']) && $_GET['prop'] > 0){
	$prop = new Emails();
	$prop->getProp($_GET['prop']);
	if($prop->Property->property_id){
		$prop->similarProps($prop->Property->pr_type_id,$prop->Property->city);
	}
	/*echo "<pre>";
	print_r($prop);
	echo "</pre>";*/
}
?>

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
	<title>Email Template</title>
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
											<!-- Top -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td class="text-12 c-grey l-grey a-right py-20" style="font-size:12px; line-height:16px; font-family:'PT Sans', Arial, sans-serif; min-width:auto !important; color:#6e6e6e; text-align:right; padding-top: 20px; padding-bottom: 20px;">
															<a href="#" target="_blank" class="link c-grey" style="text-decoration:none; color:#6e6e6e;"><span class="link c-grey" style="text-decoration:none; color:#6e6e6e;">View this email in your browser</span></a>
														</td>
													</tr>
												</table>											<!-- END Top -->

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
																				<a href="#" target="_blank"><img src="http://kiinrealty.com/dist/inc/emails/images/logo.png" width="112" height="25" border="0" alt="" /></a>
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
																									<td class="fluid-img img-center pb-50" style="font-size:0pt; line-height:0pt; text-align:center; padding-bottom: 50px;">
																										<img src="http://kiinrealty.com/adm/uploads/<?= $prop->Property->image ?>" width="268" border="0" alt="" />
																									</td>
																								</tr>
																								<tr>
																									<td class="title-36 a-center pb-15" style="font-size:36px; line-height:40px; color:#282828; font-family:'PT Sans', Arial, sans-serif; min-width:auto !important; text-align:center; padding-bottom: 15px;">
																										<strong>Request Received</strong>
																									</td>
																								</tr>
																								<tr>
																									<td class="title-22 a-center c-purple pb-15" style="font-size:22px; line-height:26px; font-family:'PT Sans', Arial, sans-serif; min-width:auto !important; text-align:center; color:#FFCB63; padding-bottom: 15px;">
																										<strong><?= $prop->Property->property_title ?></strong>
																									</td>
																								</tr>
																								<tr>
																									<td class="text-16 lh-26 a-center" style="font-size:16px; color:#6e6e6e; font-family:'PT Sans', Arial, sans-serif; min-width:auto !important; line-height: 26px; text-align:center;">
																										We have received your property information request &amp; will be sending you the information shortly. We thought you might want to keep a copy of this property in your email for future reference. We wish you a wonderful day.
																									</td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</table>
																				<!-- END Section - Intro -->

																				<?php if(!empty($prop->SimilarProps) && count($prop->SimilarProps) > 1){
																					$p1 = $prop->SimilarProps[0];
																					$p2 = $prop->SimilarProps[1];
																					 ?>
																				<!-- Section - Contact -->
																				<table width="100%" border="0" cellspacing="0" cellpadding="0">
																					<tr>
																						<td class="py-50" style="padding-top: 50px; padding-bottom: 50px;">
																							<table width="100%" border="0" cellspacing="0" cellpadding="0">
																								<tr>
																									<th class="column-top" valign="top" width="240" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;">
																										<table width="100%" border="0" cellspacing="0" cellpadding="0">
																											<tr>
																												<td class="p-30 mpx-15" style="border-radius: 10px; padding: 30px;" bgcolor="#f4f4f4">
																													<table width="100%" border="0" cellspacing="0" cellpadding="0">
																														<tr>
																															<td>
																																<table width="100%" border="0" cellspacing="0" cellpadding="0" style="position: relative; z-index: 1; margin-top: -90px;">
																																	<tr>
																																		<td class="img-center pb-20" style="font-size:0pt; line-height:0pt; text-align:center; padding-bottom: 20px;">
																																			<img src="http://kiinrealty.com/adm/uploads/<?= $p1->image ?>" width="110" border="0" alt="" />
																																		</td>
																																	</tr>
																																</table>
																															</td>
																														</tr>
																														<tr>
																															<td class="text-16 lh-26 c-black l-black a-center" style="font-size:16px; font-family:'PT Sans', Arial, sans-serif; min-width:auto !important; line-height: 26px; color:#282828; text-align:center;">
																																<?= $p1->property_title ?>
																															</td>
																														</tr>
																													</table>
																												</td>
																											</tr>
																										</table>
																									</th>
																									<th class="column-top mpb-100" valign="top" width="20" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;"></th>
																									<th class="column-top" valign="top" width="240" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal; vertical-align:top;">
																										<table width="100%" border="0" cellspacing="0" cellpadding="0">
																											<tr>
																												<td class="p-30 mpx-15" style="border-radius: 10px; padding: 30px;" bgcolor="#f4f4f4">
																													<table width="100%" border="0" cellspacing="0" cellpadding="0">
																														<tr>
																															<td>
																																<table width="100%" border="0" cellspacing="0" cellpadding="0" style="position: relative; z-index: 1; margin-top: -90px;">
																																	<tr>
																																		<td class="img-center pb-20" style="font-size:0pt; line-height:0pt; text-align:center; padding-bottom: 20px;">
																																			<img src="http://kiinrealty.com/adm/uploads/<?= $p2->image ?>" width="110" border="0" alt="" />
																																		</td>
																																	</tr>
																																</table>
																															</td>
																														</tr>
																														<tr>
																															<td class="text-16 lh-26 c-black l-black a-center" style="font-size:16px; font-family:'PT Sans', Arial, sans-serif; min-width:auto !important; line-height: 26px; color:#282828; text-align:center;">
																															 	<?= $p2->property_title; ?>
																															</td>
																														</tr>
																													</table>
																												</td>
																											</tr>
																										</table>
																									</th>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</table>
																				<!-- END Section - Contact -->
																			<?php } ?>

																				<!-- Section - Separator Line -->
																				<table width="100%" border="0" cellspacing="0" cellpadding="0">
																					<tr>
																						<td class="pb-50" style="padding-bottom: 50px;">
																							<table width="100%" border="0" cellspacing="0" cellpadding="0">
																								<tr>
																									<td class="img" height="1" bgcolor="#ebebeb" style="font-size:0pt; line-height:0pt; text-align:left;">&nbsp;</td>
																								</tr>
																							</table>
																						</td>
																					</tr>
																				</table>
																				<!-- END Section - Separator Line -->
																			</td>
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
																					<a href="https://facebook.com/kiinrealty.com" target="_blank"><img src="http://kiinrealty.com/dist/inc/emails/images/ico_facebook.png" width="34" height="34" border="0" alt="" /></a>
																				</td>
																				<td class="img" width="15" style="font-size:0pt; line-height:0pt; text-align:left;"></td>
																				<td class="img" width="34" style="font-size:0pt; line-height:0pt; text-align:left;">
																					<a href="https://instagram.com/kiinrealty.com" target="_blank"><img src="http://kiinrealty.com/dist/inc/emails/images/ico_instagram.png" width="34" height="34" border="0" alt="" /></a>
																				</td>
																			</tr>
																		</table>
																		<!-- END Socials -->
																	</td>
																</tr>
																<tr>
																	<td class="text-14 lh-24 a-center c-white l-white pb-20" style="font-size:14px; font-family:'PT Sans', Arial, sans-serif; min-width:auto !important; line-height: 24px; text-align:center; color:#ffffff; padding-bottom: 20px;">
																		MIA REALTY - MEXICO
																		<br />
																		<a href="tel:+529841310957" target="_blank" class="link c-white" style="text-decoration:none; color:#ffffff;"><span class="link c-white" style="text-decoration:none; color:#ffffff;">+52 984 131 0957</span></a> - <a href="tel:+529841161413" target="_blank" class="link c-white" style="text-decoration:none; color:#ffffff;"><span class="link c-white" style="text-decoration:none; color:#ffffff;">+52 984 116 1413</span></a>
																		<br />
																		<a href="mailto:info@website.com" target="_blank" class="link c-white" style="text-decoration:none; color:#ffffff;"><span class="link c-white" style="text-decoration:none; color:#ffffff;">info@kiinrealty.com</span></a> - <a href="www.website.com" target="_blank" class="link c-white" style="text-decoration:none; color:#ffffff;"><span class="link c-white" style="text-decoration:none; color:#ffffff;">kiinrealty.com</span></a>
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

											<!-- Bottom -->
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td class="text-12 lh-22 a-center c-grey- l-grey py-20" style="font-size:12px; color:#6e6e6e; font-family:'PT Sans', Arial, sans-serif; min-width:auto !important; line-height: 22px; text-align:center; padding-top: 20px; padding-bottom: 20px;">
															<a href="#" target="_blank" class="link c-grey" style="text-decoration:none; color:#6e6e6e;"><span class="link c-grey" style="white-space: nowrap; text-decoration:none; color:#6e6e6e;">UNSUBSCRIBE</span></a> &nbsp;|&nbsp; <a href="#" target="_blank" class="link c-grey" style="text-decoration:none; color:#6e6e6e;"><span class="link c-grey" style="white-space: nowrap; text-decoration:none; color:#6e6e6e;">WEB VERSION</span></a> &nbsp;|&nbsp; <a href="#" target="_blank" class="link c-grey" style="text-decoration:none; color:#6e6e6e;"><span class="link c-grey" style="white-space: nowrap; text-decoration:none; color:#6e6e6e;">SEND TO A FRIEND</span></a>
														</td>
													</tr>
												</table>											<!-- END Bottom -->
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
</html>
