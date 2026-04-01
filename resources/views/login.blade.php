<!DOCTYPE html>
<html>
<head>
    <title>Login with OTP</title>

    <style>
       
   body{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    /* padding:10px */
   
   }
   .container{
    width: 400px;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align:center;
    box: shadow: 0 0 10px rgba(0,0,0,0.1);
   }

   h2{
    /* background: #f0f0f0; */
    color:rgb(19, 2, 2);
    padding: auto;
   }
   input{
    width: 70%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
   }

      .btn{
        width: 30%;
        padding: 10px;
        margin: 10px 5px;

      }
      .send-btn{
        background-color: #0e67c7da;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
      }
      .login-btn{ 
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .login-btn:hover{
        background-color: #0ac633;
    }
 .send-btn:hover{
    background-color: rgb(9, 72, 123)
 }       
 .success {
    color: green;
    
 }
 .error{
    color: red;
 }
 

      

    </style>
</head>

<body>

<div class="container">

    <h2>Login with OTP</h2>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <p class="message success">{{ session('success') }}</p>
    @endif

    <!-- ERROR MESSAGE -->
    @if(session('error'))
        <p class="message error">{{ session('error') }}</p>
    @endif

    <form method="POST">
        @csrf

        <input type="email"
               name="email"
               placeholder="Enter Email"
               value="{{ old('email') }}"
               required> <br>


        <input type="password"
               name="password"
               placeholder="Enter Password"
               value="{{ old('password') }}"
               required><br>

        <input type="text"
               name="otp"
               placeholder="Enter OTP"
               value="{{ old('otp') }}"><br>

        <button type="submit" formaction="/send-otp" class="btn send-btn">
            Send OTP
        </button>

        <button type="submit" formaction="/login-otp" class="btn login-btn">
            Login
        </button>

    </form>

</div>

</body>
</html>