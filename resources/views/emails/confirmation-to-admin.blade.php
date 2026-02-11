<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb;">
                    <tr>
                        <td style="background-color: {{ $invitation->status === 'confirmed' ? '#ecfdf5' : '#fef2f2' }}; padding: 20px 30px; text-align: center;">
                            <h2 style="color: {{ $invitation->status === 'confirmed' ? '#065f46' : '#991b1b' }}; font-size: 20px; margin: 0;">
                                {{ $invitation->status === 'confirmed' ? 'Nueva Confirmacion' : 'Declino Invitacion' }}
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <table width="100%" cellpadding="8" cellspacing="0" style="font-size: 14px; color: #374151;">
                                <tr>
                                    <td style="font-weight: 600; width: 140px; vertical-align: top;">Grupo:</td>
                                    <td>{{ $invitation->group_name }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600; vertical-align: top;">Estado:</td>
                                    <td>
                                        <span style="display: inline-block; padding: 2px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; {{ $invitation->status === 'confirmed' ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                                            {{ $invitation->status === 'confirmed' ? 'Confirmado' : 'Declinado' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600; vertical-align: top;">Email:</td>
                                    <td>{{ $invitation->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: 600; vertical-align: top;">Invitados:</td>
                                    <td>
                                        @foreach($invitation->guests as $guest)
                                            <div style="margin-bottom: 4px;">
                                                {{ $guest->name ?? 'Sin nombre' }}
                                                - {{ $guest->attending ? 'Asiste' : 'No asiste' }}
                                                @if($guest->dietary_restrictions)
                                                    <br><small style="color: #6b7280;">Dieta: {{ $guest->dietary_restrictions }}</small>
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 16px 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">Wedding Manager - Notificacion Automatica</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
