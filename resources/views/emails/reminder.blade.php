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
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e8e4df;">
                    <tr>
                        <td style="background: linear-gradient(135deg, #fef3e2, #fdf2f8); padding: 30px; text-align: center;">
                            <h1 style="color: #292524; font-size: 32px; font-weight: 300; margin: 0;">Hanni & Alfonso</h1>
                            <p style="color: #b45309; font-size: 14px; margin: 8px 0 0 0;">24 de Octubre, 2025</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="color: #44403c; font-size: 16px; line-height: 1.6; margin: 0 0 16px 0;">
                                Querido/a <strong>{{ $invitation->group_name }}</strong>,
                            </p>
                            @if($reminderType === 'reminder_1')
                                <p style="color: #57534e; font-size: 15px; line-height: 1.6; margin: 0 0 16px 0;">
                                    Nuestra boda se acerca! Queremos confirmarte que estamos muy emocionados de contar contigo. Si necesitas actualizar tu confirmacion, puedes hacerlo en el siguiente enlace.
                                </p>
                            @else
                                <p style="color: #57534e; font-size: 15px; line-height: 1.6; margin: 0 0 16px 0;">
                                    Ya falta muy poco para el gran dia! Te recordamos que nuestra boda sera el 24 de octubre de 2025. Si necesitas hacer algun cambio en tu confirmacion, aun estas a tiempo.
                                </p>
                            @endif
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="padding: 16px 0;">
                                        <a href="{{ $invitation->magic_link }}" style="display: inline-block; background-color: #d97706; color: #ffffff; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-size: 16px; font-weight: 500;">
                                            Ver Mi Invitacion
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 20px 30px; text-align: center; border-top: 1px solid #e8e4df;">
                            <p style="color: #a8a29e; font-size: 12px; margin: 0;">Con carino, Hanni & Alfonso</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
