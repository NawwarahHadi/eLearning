<h2>Congratulations, {{ $user->name }}!</h2>
<p>Your application to join Al-Amin Tuition Centre has been approved.</p>

<p>You can now log in to the portal using the following credentials:</p>

<div style="background: #f4f4f4; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px;">
    <strong>Email:</strong> {{ $user->email }} <br>
    <strong>Temporary Password:</strong> {{ $password }}
</div>

<div style="border-left: 4px solid #000; padding: 10px 15px; background: #fafafa; margin-bottom: 20px;">
    <p style="margin: 0; font-weight: bold;">You will be teaching the following subject(s):</p>
    <ul style="margin-top: 5px;">
        @foreach($subjects as $subject)
            <li><strong>{{ $subject }}</strong></li>
        @endforeach
    </ul>
</div>

<p>Please change your password immediately after your first login for security purposes.</p>
<p style="text-align: center;">
    <a href="{{ route('login') }}" style="background: #000; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Click here to Login</a>
</p>
