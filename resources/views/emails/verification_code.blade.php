<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Verificación de Correo Electrónico</h1>
        <p>Hola,</p>
        <p>Tu código de verificación es: <span class="code">{{ $code }}</span></p>
        <p>Por favor, ingresa este código en la aplicación para verificar tu correo electrónico.</p>
        <p>Si no solicitaste este código, por favor ignora este mensaje.</p>
        <p>Saludos,<br>El equipo de Graus</p>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Graus. Todos los derechos reservados.</p>
        </div>
    </div>
</body>

</html>
