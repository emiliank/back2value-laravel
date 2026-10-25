// Measures rendered box sizes of CTA icons on a running dev server.
// Usage: node scripts/measure-icons.mjs <url> [screenshotPath]
const url = process.argv[2] ?? 'http://127.0.0.1:8123/sq/solutions';
const screenshot = process.argv[3] ?? null;

const res = await fetch('http://127.0.0.1:9222/json/new?' + encodeURIComponent(url), {
    method: 'PUT',
});
const target = await res.json();

const ws = new WebSocket(target.webSocketDebuggerUrl);
let id = 0;
const pending = new Map();

const send = (method, params = {}) =>
    new Promise((resolve) => {
        const messageId = ++id;
        pending.set(messageId, resolve);
        ws.send(JSON.stringify({ id: messageId, method, params }));
    });

ws.addEventListener('message', (event) => {
    const msg = JSON.parse(event.data);
    if (msg.id && pending.has(msg.id)) {
        pending.get(msg.id)(msg.result);
        pending.delete(msg.id);
    }
});

await new Promise((resolve) => ws.addEventListener('open', resolve));
await send('Page.enable');
await send('Runtime.enable');
await new Promise((r) => setTimeout(r, 2500));

const expression = `(() => {
    const out = [];
    document.querySelectorAll('.icon-badge').forEach((icon) => {
        const r = icon.getBoundingClientRect();
        const svg = icon.querySelector('svg');
        const s = svg ? svg.getBoundingClientRect() : null;
        const parent = icon.parentElement;
        out.push({
            iconClass: icon.className,
            parentClass: parent ? parent.className : null,
            iconBox: [Math.round(r.width), Math.round(r.height)],
            svgBox: s ? [Math.round(s.width), Math.round(s.height)] : null,
        });
    });
    return JSON.stringify(out, null, 2);
})()`;

const result = await send('Runtime.evaluate', { expression, returnByValue: true });
console.log(result?.result?.value ?? JSON.stringify(result));

if (screenshot) {
    const { data } = await send('Page.captureScreenshot', { format: 'png' });
    const { writeFile } = await import('node:fs/promises');
    await writeFile(screenshot, Buffer.from(data, 'base64'));
    console.log('screenshot -> ' + screenshot);
}

await send('Page.close');
ws.close();
process.exit(0);
