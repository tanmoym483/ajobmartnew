<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <title>Ajobmart Email Verification</title>

</head>

<body style="
    margin:0;
    padding:0;
    background:#f5f7fb;
    font-family:Arial,Helvetica,sans-serif;
">

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    style="padding:40px 15px;"
>

    <tr>

        <td align="center">

            <table
                width="500"
                cellpadding="0"
                cellspacing="0"
                style="
                    max-width:500px;
                    width:100%;
                    background:#ffffff;
                    border-radius:15px;
                    padding:35px;
                "
            >

                <tr>

                    <td align="center">

                        <h1 style="
                            margin:0 0 10px;
                            color:#2878f0;
                        ">
                            Ajobmart
                        </h1>

                        <p style="
                            color:#666;
                            font-size:14px;
                        ">
                            Email Verification
                        </p>

                    </td>

                </tr>


                <tr>

                    <td>

                        <p style="
                            color:#333;
                            font-size:15px;
                        ">
                            Hello,
                        </p>

                        <p style="
                            color:#555;
                            font-size:14px;
                            line-height:1.6;
                        ">
                            Use the following OTP to verify your
                            email address and continue creating
                            your Ajobmart account.
                        </p>


                        <div style="
                            margin:25px 0;
                            padding:18px;
                            background:#f1f5ff;
                            border-radius:10px;
                            text-align:center;
                        ">

                            <span style="
                                font-size:32px;
                                font-weight:bold;
                                letter-spacing:8px;
                                color:#2878f0;
                            ">
                                <?= esc($otp) ?>
                            </span>

                        </div>


                        <p style="
                            color:#888;
                            font-size:13px;
                            text-align:center;
                        ">
                            This OTP will expire in
                            <?= esc($expires) ?> minutes.
                        </p>


                        <p style="
                            color:#999;
                            font-size:12px;
                            line-height:1.5;
                            margin-top:30px;
                        ">
                            If you did not request this verification,
                            please ignore this email.
                        </p>

                    </td>

                </tr>

            </table>

        </td>

    </tr>

</table>

</body>

</html>