<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="margin: 0; padding: 0; background-color: #faf9f7; font-family: Georgia, serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #faf9f7; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e8e4df;">
                    {{-- Header --}}
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #fef3e2, #fdf2f8); padding: 40px 30px; text-align: center;">
                            <p
                                style="color: #92400e; font-size: 12px; letter-spacing: 3px; text-transform: uppercase; margin: 0 0 16px 0;">
                                Código de Verificación</p>
                            <h1 style="color: #292524; font-size: 36px; font-weight: 300; margin: 0; line-height: 1.2;">
                                Alfonso & Hannia</h1>
                            <p style="color: #b45309; font-size: 16px; margin: 12px 0 0 0;">24 de Octubre, 2026</p>
                        </td>
                    </tr>
                    {{-- Body --}}
                    <tr>
                        <td style="padding: 36px 30px;">
                            <p style="color: #44403c; font-size: 16px; line-height: 1.6; margin: 0 0 16px 0;">
                                Hola, <strong>{{ $invitation->group_name }}</strong>
                            </p>
                            <p style="color: #57534e; font-size: 15px; line-height: 1.6; margin: 0 0 28px 0;">
                                Tu código de verificación para acceder a tu invitación es:
                            </p>
                            {{-- OTP Code --}}
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 28px;">
                                <tr>
                                    <td align="center">
                                        <div
                                            style="display: inline-block; background-color: #fef3e2; border: 1px solid #f5d9a8; border-radius: 10px; padding: 20px 36px;">
                                            <span
                                                style="font-size: 40px; font-weight: 600; letter-spacing: 14px; color: #92400e; font-family: 'Courier New', monospace;">{{ $otp }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <p
                                style="color: #78716c; font-size: 13px; line-height: 1.6; margin: 0; text-align: center;">
                                Este código expira en <strong>10 minutos</strong>.
                            </p>
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 20px 30px; text-align: center; border-top: 1px solid #e8e4df;">
                            <p style="color: #a8a29e; font-size: 12px; margin: 0;">
                                Si no solicitaste este código, puedes ignorar este correo.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
