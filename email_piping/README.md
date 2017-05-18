

From within cPanel, establish an e-mail forwarder that "pipes" to the correct email address.

Example:

pipetest@castlamp.com
    -- to -->
|/home/yoursite/public_html/clients/custom/plugins/email_piping/pipe.php




LOGIC

1. If the "to" matches an employee's email, the email is considered valid and the process progresses.
2a. If the "from" matches a member, it is assigned to that member.
2b. If no member is found, it tries to find a contact, and if found, assigns it to that contact.
2c. If no contact or member is found, it either (option) skips the note creation, or creates a new contact.
3. The piping tool now creates a note and adds it to the contact/member's notes.
4. The piping tool also creates a form submission for one-click reply to the email from within Zenbership.
5. Finally the email is forwarded along to the inbox.
