<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
  <head>
    <link rel="stylesheet" type="text/css" hs-webfonts="true" href="https://fonts.googleapis.com/css?family=Lato|Lato:i,b,bi">
    
    <title>Contact Form email</title>
    
    <meta property="og:title" content="Email template">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style type="text/css">
      a{ 
        text-decoration: underline;
        color: inherit;
        font-weight: bold;
        color: #253342;
      }

      h1{
        font-size: 56px;
      }

      h2{
        font-size: 28px;
        font-weight: 900; 
      }

      p {
        font-weight: 100;
      }

      td {
      vertical-align: top;
      }

      #email {
        margin: auto;
        width: 600px;
        background-color: white;
        /* background-color: #c1cbd4; */
      }

      .subtle-link {
        font-size: 9px; 
        text-transform:uppercase; 
        letter-spacing: 1px;
      }

      .entity{
        font-size:16px;
      }
    </style>

  </head>
  <body bgcolor="#F5F8FA" style="width: 100%; margin: auto 0; padding:0; font-family:Lato, sans-serif; font-size:18px; color:#33475B; word-break:break-word" >      
    <div id="email"> 
      <table role="presentation" border="0" bgcolor="#c1cbd4" cellpadding="0" cellspacing="10px" style="padding: 20px 20px 20px 30px;" width="100%">
        <tr>
          <td>
            <h2> Nouveau message de : <?= $firstname ?> <?= $lastname ?></h2>
            <p>
              <a class="subtle-link" href="#"><span class="entity">&#9993; </span> <?= $this->getUserEmail() ?> </a>
            </p>
            <p>
              <?= $this->getMessage() ?> 
            </p>
          </td> 
        </tr>
      </table>
      
      <table role="presentation" bgcolor="#F5F8FA" width="100%" >
        <tr>
          <td align="left" style="padding: 10px 10px;">
            <p style="color:#798797"> Envoyé depuis le formulaire de contact du Blog </p>    
          </td>
        </tr>
      </table> 
    </div>
  </body>
</html>