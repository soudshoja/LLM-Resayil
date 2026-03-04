@component('mail::message')
# New User Registration — LLM Resayil

A new user has just registered on the LLM Resayil portal.

**Name:** {{ $name ?: '(not provided)' }}

**Email:** {{ $email ?: '(not provided)' }}

**Phone:** {{ $phone }}

**Registered At:** {{ $registeredAt }}

---

*This notification was sent automatically from llm.resayil.io*
@endcomponent
