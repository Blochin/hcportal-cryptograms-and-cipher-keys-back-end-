<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office"
    style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background: #f1f1f1;margin: 0 auto !important;padding: 0 !important;height: 100% !important;width: 100% !important;">

<head style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
    <meta charset="utf-8" style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
    <!-- utf-8 works for most cases -->
    <meta name="viewport" content="width=device-width"
        style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
    <!-- Forcing initial-scale shouldn't be necessary -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"
        style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
    <!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting"
        style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
    <!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"></title>
    <!-- The title tag shows in email notifications, like Android 4.4. -->


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;600&display=swap" rel="stylesheet">

    <!-- CSS Reset : BEGIN -->
    <style style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background: #f1f1f1;
            font-family: 'Poppins', sans-serif;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* What it does: Prevents Windows 10 Mail from underlining links despite inline CSS. Styles for underlined links should be inline. */
        a {
            text-decoration: none;
        }

        /* What it does: A work-around for email clients meddling in triggered links. */
        *[x-apple-data-detectors],
        /* iOS */
        .unstyle-auto-detected-links *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* What it does: Prevents Gmail from displaying a download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }

        /* What it does: Prevents Gmail from changing the text color in conversation threads. */
        .im {
            color: inherit !important;
        }

        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img+div {
            display: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */

        /* iPhone 4, 4S, 5, 5S, 5C, and 5SE */
        @media only screen and (min-device-width: 320px) and (max-device-width: 374px) {
            u~div .email-container {
                min-width: 320px !important;
            }
        }

        /* iPhone 6, 6S, 7, 8, and X */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {
            u~div .email-container {
                min-width: 375px !important;
            }
        }

        /* iPhone 6+, 7+, and 8+ */
        @media only screen and (min-device-width: 414px) {
            u~div .email-container {
                min-width: 414px !important;
            }
        }

    </style>

    <!-- CSS Reset : END -->

    <!-- Progressive Enhancements : BEGIN -->
    <style style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
        .primary {
            background: #035fc0;
        }

        .bg_white {
            background: #ffffff;
        }

        .bg_light {
            background: #fafafa;
        }

        .bg_black {
            background: url({{ url('img/footer.png') }})
        }

        .bg_dark {
            background: rgba(0, 0, 0, .8);
        }

        .email-section {
            padding: 2.5em;
        }

        /*BUTTON*/
        .btn {
            padding: 10px 15px;
        }

        .btn.btn-primary {
            border-radius: 30px;
            background: #035fc0;
            color: #ffffff;
        }



        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: #000000;
            margin-top: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
            font-size: 15px;
            line-height: 1.8;
            color: rgba(0, 0, 0, .4);
        }

        a {
            color: #035fc0;
        }

        table {}

        /*LOGO*/

        .logo h1 {
            margin: 0;
        }

        .logo h1 a {
            color: #000;
            font-size: 20px;
            font-weight: 300;
            text-transform: uppercase;
        }

        /*HERO*/
        .hero {
            position: relative;
        }

        .hero img {}

        .hero .text {
            color: rgba(255, 255, 255, .8);
        }

        .hero .text h2 {
            color: #035fc0;
            font-size: 30px;
            margin-bottom: 0;
        }


        /*HEADING SECTION*/
        .heading-section {}

        .heading-section h2 {
            color: #000000;
            font-size: 28px;
            margin-top: 0;
            line-height: 1.4;
        }

        .heading-section .subheading {
            margin-bottom: 20px !important;
            display: inline-block;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(0, 0, 0, .4);
            position: relative;
        }

        .heading-section .subheading::after {
            position: absolute;
            left: 0;
            right: 0;
            bottom: -10px;
            content: '';
            width: 100%;
            height: 2px;
            background: #035fc0;
            margin: 0 auto;
        }

        .heading-section-white {
            color: rgba(255, 255, 255, .8);
        }

        .heading-section-white h2 {
            font-size: 28px;
            font-family:
                line-height: 1;
            padding-bottom: 0;
        }

        .heading-section-white h2 {
            color: #ffffff;
        }

        .heading-section-white .subheading {
            margin-bottom: 0;
            display: inline-block;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: rgba(255, 255, 255, .4);
        }


        .icon {
            text-align: center;
        }

        .icon img {}


        /*SERVICES*/
        .text-services {
            padding: 10px 10px 0;
            text-align: center;
        }

        .text-services h3 {
            font-size: 20px;
        }

        /*BLOG*/
        .text-services .meta {
            text-transform: uppercase;
            font-size: 14px;
        }

        /*TESTIMONY*/
        .text-testimony .name {
            margin: 0;
        }

        .text-testimony .position {
            color: rgba(0, 0, 0, .3);

        }


        /*VIDEO*/
        .img {
            width: 100%;
            height: auto;
            position: relative;
        }

        .img .icon {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            bottom: 0;
            margin-top: -25px;
        }

        .img .icon a {
            display: block;
            width: 60px;
            position: absolute;
            top: 0;
            left: 50%;
            margin-left: -25px;
        }



        /*COUNTER*/
        .counter-text {
            text-align: center;
        }

        .counter-text .num {
            display: block;
            color: #ffffff;
            font-size: 34px;
            font-weight: 300;
        }

        .counter-text .name {
            display: block;
            color: rgba(255, 255, 255, .9);
            font-size: 13px;
        }


        /*FOOTER*/

        .footer {
            color: rgba(255, 255, 255, .5);

        }

        .footer .heading {
            color: #ffffff;
            font-size: 20px;
        }

        .footer ul {
            margin: 0;
            padding: 0;
        }

        .footer ul li {
            list-style: none;
            margin-bottom: 10px;
        }

        .footer ul li a {
            color: rgba(255, 255, 255, 1);
        }


        @media screen and (max-width: 500px) {

            .icon {
                text-align: left;
            }

            .text-services {
                padding-left: 0;
                padding-right: 20px;
                text-align: left;
            }

        }

    </style>


</head>

<body width="100%"
    style="margin: 0 auto !important;padding: 0 !important;mso-line-height-rule: exactly;background-color: #fff;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background: #f1f1f1;font-family: 'Poppins', sans-serif;font-weight: 300;font-size: 15px;line-height: 1.8;color: rgba(0,0,0,.4);height: 100% !important;width: 100% !important;">
    <center style="width: 100%;background-color: #f1f1f1;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
        <div
            style="display: none;font-size: 1px;max-height: 0px;max-width: 0px;opacity: 0;overflow: hidden;mso-hide: all;font-family: sans-serif;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
            &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
        </div>
        <div style="max-width: 600px;margin: 0 auto;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;"
            class="email-container">
            <!-- BEGIN BODY -->
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                style="margin: auto;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border-spacing: 0 !important;border-collapse: collapse !important;table-layout: fixed !important;">
                <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                    <td class="bg_white logo"
                        style="padding: 1em 2.5em 1em;text-align: center;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background: #141414;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                        <h1
                            style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #000000;margin-top: 0;margin: 0;">
                            <a href="{{ url(env('APP_URL')) }}"
                                style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;text-decoration: none;color: #000;font-size: 20px;font-weight: 300;text-transform: uppercase;"><img
                                    src="{{ $message->embed(asset('images/logo.png')) }}" width="200px"
                                    alt="{{ url(env('APP_NAME')) }}"
                                    style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;-ms-interpolation-mode: bicubic;"></a>
                        </h1>
                    </td>
                </tr><!-- end tr -->

                <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                    <td class="bg_white"
                        style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background: #ffffff;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                            style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border-spacing: 0 !important;border-collapse: collapse !important;table-layout: fixed !important;margin: 0 auto !important;">
                            <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                <td class="bg_white email-section"
                                    style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background: #ffffff;padding: 2.5em;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                                    <div class="heading-section"
                                        style="text-align: center;padding: 0 30px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                        <span class="subheading"
                                            style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;display: inline-block;font-size: 13px;text-transform: uppercase;letter-spacing: 2px;color: rgba(0,0,0,.4);position: relative;margin-bottom: 20px !important;">
                                            Cipher Key Name - {{ $key->name }}
                                        </span>
                                        <h2
                                            style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;color: #000000;margin-top: 0;font-size: 28px;line-height: 1.4;">
                                            The status of the cipher key with name {{ $key->name }} you
                                            added has changed to <b>{{ $key->state['title'] }}<b>.
                                        </h2>
                                        <p style="color:#000;margin: 0"><b>Cipher Key Name:</b>
                                            {{ $key->name }}</p>

                                        @if ($key->note)
                                            <p style="color:#000;margin: 0"><b>Note:</b> {{ $key->note }}</p>
                                        @endif

                                    </div>
                                </td>
                            </tr><!-- end: tr -->

                        </table>

                    </td>
                </tr><!-- end:tr -->
            </table>
            <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                style="margin: auto;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border-spacing: 0 !important;border-collapse: collapse !important;table-layout: fixed !important;">
                <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                    <td valign="middle" class="bg_black footer email-section"
                        style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background: #000;padding: 2.5em;color: rgba(255,255,255,.5);mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                        <table
                            style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border-spacing: 0 !important;border-collapse: collapse !important;table-layout: fixed !important;margin: 0 auto !important;">
                            <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                <td valign="top" width="70%"
                                    style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                        style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border-spacing: 0 !important;border-collapse: collapse !important;table-layout: fixed !important;margin: 0 auto !important;">
                                        <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <td
                                                style="text-align: left;padding-right: 10px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                                                <p style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                    &copy; {{ now()->format('Y') }} {{ env('APP_NAME') }}.</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td valign="top" width="20%"
                                    style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                                        style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;border-spacing: 0 !important;border-collapse: collapse !important;table-layout: fixed !important;margin: 0 auto !important;">
                                        <tr style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                            <td
                                                style="text-align: right;padding-left: 5px;padding-right: 5px;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;mso-table-lspace: 0pt !important;mso-table-rspace: 0pt !important;">
                                                {{-- <p
                                                    style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                    <ahref="#"style="color:rgba(255,255,255,.4);"
                                                        style="-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;">
                                                        Unsubcribe</ahref="#"style="color:rgba(255,255,255,.4);">
                                                </p> --}}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </div>
    </center>
</body>

</html>
