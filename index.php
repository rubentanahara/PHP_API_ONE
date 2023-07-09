<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>API - Pruebas</title>
   <link rel="stylesheet" href="assets/styles.css" type="text/css">
   <style>
      .required {
         color: red;
      }
   </style>
</head>

<body>

   <div class="container">
      <h1>API de Pruebas</h1>
      <div class="divbody">
         <h3>Auth - Login</h3>
         <code>
            POST /auth
            <br>
            {
            <br>
            "usuario": "<span class="required">->REQUERIDO</span>",
            <br>
            "password": "<span class="required">->REQUERIDO</span>"
            <br>
            }
         </code>
      </div>
      <div class="divbody">
         <h3>Pacientes</h3>
         <code>
            GET /pacientes?page=$numeroPagina
            <br>
            GET /pacientes?id=$idPaciente
         </code>
         <br>
         <code>
            POST /pacientes
            <br>
            {
            <br>
            "nombre": "<span class="required">->REQUERIDO</span>",
            <br>
            "dni": "<span class="required">->REQUERIDO</span>",
            <br>
            "correo": "<span class="required">->REQUERIDO</span>",
            <br>
            "codigoPostal": "",
            <br>
            "genero": "",
            <br>
            "telefono": "",
            <br>
            "fechaNacimiento": "",
            <br>
            "token": "<span class="required">->REQUERIDO</span>"
            <br>
            }
         </code>
         <br>
         <code>
            PUT /pacientes
            <br>
            {
            <br>
            "nombre": "",
            <br>
            "dni": "",
            <br>
            "correo": "",
            <br>
            "codigoPostal": "",
            <br>
            "genero": "",
            <br>
            "telefono": "",
            <br>
            "fechaNacimiento": "",
            <br>
            "token": "<span class="required">->REQUERIDO</span>",
            <br>
            "pacienteId": "<span class="required">->REQUERIDO</span>"
            <br>
            }
         </code>
         <br>
         <code>
            DELETE /pacientes
            <br>
            {
            <br>
            "token": "<span class="required">->REQUERIDO</span>",
            <br>
            "pacienteId": "<span class="required">->REQUERIDO</span>"
            <br>
            }
         </code>
      </div>
   </div>
</body>

</html>