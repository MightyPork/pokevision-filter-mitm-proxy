# PokéVision Filter MiTM Proxy

Tiny PHP proxy that injects the PokéVision Filter extension into the page for use on mobile (where extensions generally don't work).

## What's it doing?

- It serves as a proxy which injects the [PokéVision Filter](https://github.com/MightyPork/pokevision-filter) scripts and styles into the page.
- It then uses [miniProxy](https://github.com/joshdick/miniProxy) to make map data loading possible (kudos to Joshua Dick for this part). This is because modern browsers otherwise throw a fit over missing CORS headers.
- Additionaly, it hides ads and tracking code from the page to reduce mobile data usage & make the experience better. If you feel bad about hiding the ads, feel free to compensate the authors with a donation (or remove the ad hiding code, it's in your hands).
- And because this is mostly meant for mobile browsers, we also inject some CSS to make the UI more mobile friendly.

## How to use it

- First, you need a public web server that can run PHP.
- You could use a Raspberry Pi for this, with some port forwarding.
- Then simply dump this repo into it's document root and you're good to go.
