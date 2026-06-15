# Insecure Ecommerce Lab Report Notes

This module is intentionally vulnerable for coursework screenshots and code analysis only. Do not deploy it.

## Part 1: Authentication Module Analysis

Affected files:

- `app/Http/Controllers/AuthController.php`
- `resources/views/lab/auth/register.blade.php`
- `resources/views/lab/auth/login.blade.php`

Identified vulnerabilities and impacts:

1. Missing input validation
   - The registration form accepts empty, malformed, or oversized `name`, `email`, and `password` values.
   - Impact: bad data enters the database, duplicate accounts are possible, and malicious payloads can be stored.

2. Weak password handling
   - Passwords are stored as `md5($password)`.
   - Impact: MD5 is fast and unsalted, so leaked password hashes are easy to crack with common wordlists.

3. SQL injection
   - Registration and login concatenate user input directly into SQL strings.
   - Impact: an attacker can alter query logic and potentially bypass login or damage data.
   - Local lab example: put an SQL condition in the email field to make the `WHERE` clause always true.

4. Stored XSS
   - User names are displayed with `{!! session('insecure_user_name') !!}` in the transaction view.
   - Impact: script entered during registration can execute later in the browser.
   - Local lab example: register with a name containing a simple script tag and then view `/lab/transactions`.

5. Session fixation risk
   - `/login?sid=known-session-id` accepts a session id from the URL.
   - The login method does not call `session()->regenerate()`.
   - Impact: if a victim logs in using a known session id, that id can remain valid after authentication.

Recommended improvements:

- Validate requests with Laravel validation rules such as `required`, `email`, `max`, `confirmed`, and unique email checks.
- Use Laravel authentication helpers and `Hash::make()` / `Hash::check()` instead of MD5.
- Use Eloquent or parameterized queries instead of concatenated SQL.
- Escape output with `{{ }}` instead of `{!! !!}` unless content has been sanitized.
- Regenerate the session id after successful login with `$request->session()->regenerate()`.
- Never accept a session id from a request parameter.

## Part 2: Transaction and Session Module Analysis

Affected files:

- `app/Http/Controllers/TransactionController.php`
- `resources/views/lab/transactions/index.blade.php`

Identified threats and potential exploits:

1. Session fixation or hijacking
   - Authentication stores only `insecure_user_id` and `insecure_user_name` in the session without regeneration.
   - Impact: session id reuse can allow unauthorized access if the id is known.

2. Client-side state manipulation
   - The create transaction form trusts hidden fields for `user_id` and `price`.
   - Impact: a user can change the hidden `price` or `user_id` in browser developer tools before submitting.

3. Authorization gaps
   - `/transactions?user_id=2` shows another user's transactions.
   - `/transactions/{id}/status` allows any logged-in user to update any transaction by id.
   - Impact: users can view or modify records that do not belong to them.

4. Input/output sanitization flaws
   - Product name, address, note, and status are stored from request data and printed with `{!! !!}`.
   - Impact: stored XSS can execute when the transaction list is opened.

5. SQL injection in transaction creation and status update
   - The module builds SQL statements using request values.
   - Impact: malicious input can change database queries.

Suggested improvements:

- Use Laravel policies or gates to check ownership before viewing or updating transactions.
- Derive `user_id` from the authenticated session, not from hidden form fields.
- Derive price from a trusted products table on the server, not from the browser.
- Validate transaction fields with strict rules for quantity, status, product id, and address length.
- Use parameterized queries or Eloquent models.
- Escape transaction output with `{{ }}`.
- Regenerate sessions after login, invalidate sessions on logout, and rely on Laravel's built-in authentication stack.

Suggested screenshots:

- Registration form at `/register`.
- Login form at `/login`.
- Transaction form and table at `/transactions`.
- URL manipulation example showing `/transactions?user_id=2`.
- Code snippets from the two insecure controllers showing raw SQL and missing authorization.
