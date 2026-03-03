@component('mail::message')
# New Contact Form Submission

You have received a new message via the LLM Resayil contact form.

## Details

**Full Name:** {{ $fullName }}

**Email:** {{ $email }}

**Mobile:** {{ $mobile }}

**Message:**
{{ $message }}

---

*This email was sent from the contact form at llm.resayil.io*

</component>
