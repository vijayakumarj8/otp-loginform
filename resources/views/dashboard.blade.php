<h2>Welcome to Dashboard</h2>


 <style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}

/* Tabs container */
.tabs {
    margin-bottom: 20px;
}

/* Buttons */
.tab-btn {
    padding: 10px 18px;
    border: none;
    cursor: pointer;
    border-radius: 6px;
    margin-right: 10px;
    background: #ddd;
    transition: 0.3s;
    font-weight: bold;
}

.tab-btn:hover {
    background: #bbb;
}

.active-tab {
    background: #4a90e2;
    color: white;
}

/* Card design */
.card {
    background: white;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 1px 10px rgba(0,0,0,0.1);
    width: 400px;
}

/* Inputs */
input {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #ccc;
    outline: none;
}

input:focus {
    border-color: #4a90e2;
}

/* Buttons */
.submit-btn {
    width: 100%;
    padding: 10px;
    background: #4a90e2;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
}

.submit-btn:hover {
    background: #357ab7;
}

/* Messages */
.success {
    color: green;
    background: #e6ffe6;
    padding: 8px;
    border-radius: 6px;
    margin-bottom: 10px;
}

.error {
    color: red;
    background: #ffe6e6;
    padding: 8px;
    border-radius: 6px;
    margin-bottom: 10px;
}


.container {
    display: flex;
    justify-content: center;
    margin-top: 30px;
}
</style> 

<!-- Tabs -->
<div class="tabs">
    <button onclick="showTab('reset')" id="resetBtn" class="tab-btn active-tab">
        Reset Password
    </button>
    <button onclick="showTab('logout')" id="logoutBtn" class="tab-btn">
        Logout
    </button>
</div>

<div class="container">

<!-- ================= RESET PASSWORD ================= -->
<div id="resetTab" class="card">

    <h3>Reset Password</h3>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reset.password') }}">
        @csrf

        <input type="password"
               name="current_password"
               placeholder="Current Password"
               value="{{ old('current_password') }}">

        <input type="password"
               name="new_password"
               placeholder="New Password">

        <input type="password"
               name="confirm_password"
               placeholder="Confirm Password">

        <button class="submit-btn" type="submit">Update Password</button>
    </form>
</div>

 {{-- LOGOUt --}}
<div id="logoutTab" class="card" style="display:none;">

    <h3>Logout</h3>

    <form id="logoutForm" method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="submit-btn" type="button" onclick="confirmLogout()">
            Logout
        </button>
    </form>

</div>

</div>

<!-- ================= SCRIPT ================= -->
<script>
function showTab(tab) {
    document.getElementById('resetTab').style.display = 'none';
    document.getElementById('logoutTab').style.display = 'none';

    document.getElementById('resetBtn').classList.remove('active-tab');
    document.getElementById('logoutBtn').classList.remove('active-tab');

    if (tab === 'reset') {
        document.getElementById('resetTab').style.display = 'block';
        document.getElementById('resetBtn').classList.add('active-tab');
    } else {
        document.getElementById('logoutTab').style.display = 'block';
        document.getElementById('logoutBtn').classList.add('active-tab');
    }
}

function confirmLogout() {
    if (confirm("Are you sure you want to logout?")) {
        document.getElementById('logoutForm').submit();
    }
}

// default tab
showTab('reset');
</script>