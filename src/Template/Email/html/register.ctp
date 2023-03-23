<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <title>Email Template</title>
      <meta name="viewport" content="width=device-width">
</head>
<body style="min-width: 100%;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;margin: 0;padding: 0;direction: ltr;background: #eee;width: 100% !important;">
      <table class="body" width="100%">
            <tr>
      <td class="center" align="center" valign="top">
            <!-- BEGIN: Header -->
            <table class="page-header" align="center" style="width: 100%;border-bottom:3px double #888; background:#f7f7f7;">
                  <tr>
                        <td class="center" align="center">
                        <!-- BEGIN: Header Container --> <!-- BEGIN: Logo -->
                        <table class="six columns">
                              <tr>
                                    <td class="vertical-middle" style="padding-top: 0;padding-bottom: 0;vertical-align: middle; text-align:center;">
                                          <img src="<?php echo $source; ?>" alt="{title}" title="{title}" width="200" height="40" />
                                    </td>
                              </tr>
                        </table>
                        <!-- END: Logo --> <!-- END: Header Container -->
                        </td>
                  </tr>
            </table>
            <!-- END: Header -->
            <!-- BEGIN: Content -->
            <table class="container content" align="center" width="100%">

            <?php echo $mailContent; ?>

            </table>
            <!-- END: Content -->
            <!-- BEGIN: Footer -->


            <table class="page-footer" align="center" style="width: 100%;border-top:3px double #888; background:#f7f7f7;">
                  <tr>
                  <td class="center" align="center" style="vertical-align: middle;color: #333;">
                        <table class="container" align="center" width="100%"> <tr><td style="vertical-align: middle;color: #333;">
                              <!-- BEGIN: Unsubscribet -->
                                    <table class="container" align="center" width="100%"> 
                                          <tr> 
                                          <td> <!-- BEGIN: Unsubscribet -->
                                           <span style="font-size:12px;"> <i style="color:#333;">This is a system generated email and reply is not required.</i></span>
                                          </td>
                                          <td class="vertical-middle" style="color:#888888;">&copy; <?php echo $storename; ?>.</td>  
                                          </tr>
                                    </table>
                                    <!-- END: Unsubscribe -->
                        </td></tr></table>
                  </td> </tr></table>
                  <!-- END: Footer -->
                  </td>
            </tr>
      </table>
</body>
</html>
