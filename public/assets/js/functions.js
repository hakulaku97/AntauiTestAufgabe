document.getElementById('login-form').addEventListener('submit', function(event) {
    const fullQualifiedDomainRegexPattern = /^[a-zA-Z0-9][a-zA-Z0-9._-]*@[a-zA-Z0-9][a-zA-Z0-9.-]*\.[a-zA-Z]{2,}$/;
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    let errors= [];

    // Client-side validation if the username is set
    if (!username) {
        errors.push('Username is required');
    }

    // Client-side validation if the password is set
    if (!password) {
        errors.push('Password is required');
    }

    // Client-side validation if username is a full-qualified-domain
    if (!fullQualifiedDomainRegexPattern.test(username)) {
        errors.push('Username must be in the format user@host.domain');
    }

    if (errors.length) {
        event.preventDefault();
        alert(errors.join('\n'));
    }
});