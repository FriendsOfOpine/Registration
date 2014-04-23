Registration
============

Service for managing aspects of event registration

Overview
++++++++

*Authentication*

A user goes to the /Registration/{event-slug} route.

The user must create and account, login or register.

*Options*
The event is validated.

The user is presented with the available registration options.

The user submits the form, it is validated to ensure that they made a valid selection.

*Attendees*

*Payment*

*Receipt*

Architecture
++++++++++++

The registration component uses the existing Authenticaion, Form and PubSub services to usher the guide the user through a multi-step event registration process.

From the event manager, the administrator can set custom applications for Options, Attendees, Payments and Receipts.