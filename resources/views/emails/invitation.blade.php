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
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #fef3e2, #fdf2f8); padding: 40px 30px; text-align: center;">
                            <p style="color: #92400e; font-size: 12px; letter-spacing: 3px; text-transform: uppercase; margin: 0 0 16px 0;">Estas cordialmente invitado</p>
                            <h1 style="color: #292524; font-size: 42px; font-weight: 300; margin: 0; line-height: 1.2;">Hanni & Alfonso</h1>
                            <p style="color: #b45309; font-size: 18px; margin: 16px 0 0 0;">24 de Octubre, 2025</p>
                        </td>
                    </tr>
                    {{-- Body --}}
                    <tr>
                        <td style="padding: 30px;">
                            <p style="color: #44403c; font-size: 16px; line-height: 1.6; margin: 0 0 16px 0;">
                                Querido/a <strong>{{ $invitation->group_name }}</strong>,
                            </p>
                            <p style="color: #57534e; font-size: 15px; line-height: 1.6; margin: 0 0 16px 0;">
                                Con mucha alegria te invitamos a celebrar nuestra boda. Seria un honor contar con tu presencia en este dia tan especial para nosotros.
                            </p>
                            @if($invitation->personal_message)
                                <p style="color: #57534e; font-size: 15px; line-height: 1.6; margin: 0 0 16px 0; font-style: italic; padding: 16px; background-color: #fefce8; border-radius: 8px;">
                                    {{ $invitation->personal_message }}
                                </p>
                            @endif
                            <p style="color: #57534e; font-size: 14px; margin: 0 0 24px 0;">
                                Tienes {{ $invitation->max_guests }} {{ $invitation->max_guests > 1 ? 'lugares reservados' : 'lugar reservado' }}.
                            </p>
                            {{-- CTA Button --}}
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $invitation->magic_link }}" style="display: inline-block; background-color: #d97706; color: #ffffff; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-size: 16px; font-weight: 500;">
                                            Confirmar Asistencia
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 20px 30px; text-align: center; border-top: 1px solid #e8e4df;">
                            <p style="color: #a8a29e; font-size: 12px; margin: 0;">
                                Este enlace es personal e intransferible.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
