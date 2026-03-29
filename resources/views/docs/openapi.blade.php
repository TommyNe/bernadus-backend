<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bernadus API Dokumentation</title>
        <style>
            :root {
                color-scheme: light;
                --bg: #f4efe4;
                --surface: rgba(255, 252, 245, 0.9);
                --surface-strong: #fffdf7;
                --border: #d8c7a8;
                --text: #2f2417;
                --muted: #6f604b;
                --accent: #8f2d1f;
                --accent-soft: #efe0cb;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                font-family: Georgia, "Times New Roman", serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(143, 45, 31, 0.12), transparent 30%),
                    linear-gradient(135deg, #f7f1e7 0%, #efe4d2 100%);
                min-height: 100vh;
            }

            .layout {
                width: min(1100px, calc(100% - 2rem));
                margin: 0 auto;
                padding: 2rem 0 3rem;
            }

            .hero,
            .panel {
                background: var(--surface);
                border: 1px solid var(--border);
                border-radius: 20px;
                box-shadow: 0 18px 40px rgba(82, 56, 24, 0.08);
                backdrop-filter: blur(10px);
            }

            .hero {
                padding: 2rem;
                margin-bottom: 1.5rem;
            }

            .eyebrow {
                color: var(--accent);
                font-size: 0.8rem;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                margin: 0 0 0.75rem;
            }

            h1 {
                margin: 0;
                font-size: clamp(2rem, 5vw, 3.5rem);
                line-height: 0.95;
            }

            .summary {
                max-width: 60ch;
                color: var(--muted);
                font-size: 1.05rem;
                line-height: 1.6;
                margin: 1rem 0 1.5rem;
            }

            .links {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.85rem 1.1rem;
                border-radius: 999px;
                border: 1px solid var(--border);
                text-decoration: none;
                color: var(--text);
                background: var(--surface-strong);
                font-weight: 700;
            }

            .button.primary {
                background: var(--accent);
                border-color: var(--accent);
                color: #fffaf4;
            }

            .grid {
                display: grid;
                grid-template-columns: 280px minmax(0, 1fr);
                gap: 1rem;
            }

            .panel {
                padding: 1rem;
            }

            .panel h2 {
                margin: 0 0 1rem;
                font-size: 1rem;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: var(--muted);
            }

            .route-list {
                display: grid;
                gap: 0.5rem;
            }

            .route-button {
                width: 100%;
                text-align: left;
                border: 1px solid var(--border);
                background: var(--surface-strong);
                border-radius: 14px;
                padding: 0.8rem 0.9rem;
                cursor: pointer;
                color: var(--text);
            }

            .route-button.active {
                background: var(--accent-soft);
                border-color: var(--accent);
            }

            .route-method {
                display: inline-block;
                min-width: 3.75rem;
                margin-right: 0.75rem;
                color: var(--accent);
                font-weight: 700;
            }

            .details {
                display: grid;
                gap: 1rem;
            }

            .card {
                border: 1px solid var(--border);
                border-radius: 16px;
                padding: 1rem;
                background: var(--surface-strong);
            }

            .card h3 {
                margin: 0 0 0.75rem;
                font-size: 0.95rem;
                letter-spacing: 0.04em;
                text-transform: uppercase;
                color: var(--muted);
            }

            code,
            pre {
                font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, monospace;
            }

            .path {
                font-size: 1.1rem;
                font-weight: 700;
                overflow-wrap: anywhere;
            }

            .meta {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
                margin-top: 0.75rem;
            }

            .pill {
                padding: 0.35rem 0.55rem;
                border-radius: 999px;
                background: var(--accent-soft);
                color: var(--accent);
                font-size: 0.85rem;
            }

            .kv {
                display: grid;
                gap: 0.75rem;
            }

            .kv-row {
                padding-bottom: 0.75rem;
                border-bottom: 1px solid rgba(216, 199, 168, 0.6);
            }

            .kv-row:last-child {
                border-bottom: 0;
                padding-bottom: 0;
            }

            .label {
                font-size: 0.8rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: var(--muted);
                margin-bottom: 0.2rem;
            }

            pre {
                margin: 0;
                white-space: pre-wrap;
                word-break: break-word;
                max-height: 28rem;
                overflow: auto;
                background: #2b2115;
                color: #f9efe1;
                border-radius: 14px;
                padding: 1rem;
            }

            .empty {
                color: var(--muted);
            }

            @media (max-width: 900px) {
                .grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <div class="layout">
            <section class="hero">
                <p class="eyebrow">OpenAPI 3.1</p>
                <h1>Bernadus API</h1>
                <p class="summary">
                    Diese Seite liest die aktuelle <code>openapi.json</code> aus der Anwendung und zeigt die
                    dokumentierten Endpunkte direkt im Browser an.
                </p>
                <div class="links">
                    <a class="button primary" href="{{ route('openapi.json') }}">JSON abrufen</a>
                    <a class="button" href="#details">Endpunkte ansehen</a>
                </div>
            </section>

            <section class="grid" id="details">
                <aside class="panel">
                    <h2>Endpunkte</h2>
                    <div class="route-list" id="route-list">
                        <p class="empty">Lade Spezifikation...</p>
                    </div>
                </aside>

                <main class="details">
                    <section class="panel">
                        <h2>API Info</h2>
                        <div class="card">
                            <div class="path" id="api-title">Lade...</div>
                            <div class="meta">
                                <span class="pill" id="api-version"></span>
                            </div>
                            <p class="summary" id="api-description"></p>
                        </div>
                    </section>

                    <section class="panel">
                        <h2>Details</h2>
                        <div class="card" id="endpoint-card">
                            <div class="empty">Waehle links einen Endpunkt aus.</div>
                        </div>
                    </section>
                </main>
            </section>
        </div>

        <script>
            const routeList = document.getElementById('route-list');
            const endpointCard = document.getElementById('endpoint-card');
            const apiTitle = document.getElementById('api-title');
            const apiVersion = document.getElementById('api-version');
            const apiDescription = document.getElementById('api-description');

            const renderValue = (value) => {
                if (value === undefined) {
                    return 'nicht angegeben';
                }

                return JSON.stringify(value, null, 2);
            };

            const renderEndpoint = (path, method, operation) => {
                const parameters = operation.parameters ?? [];
                const responses = operation.responses ?? {};

                endpointCard.innerHTML = `
                    <div class="path"><span class="route-method">${method.toUpperCase()}</span>${path}</div>
                    <div class="meta">
                        ${(operation.tags ?? []).map((tag) => `<span class="pill">${tag}</span>`).join('')}
                    </div>
                    <div class="kv" style="margin-top: 1rem;">
                        <div class="kv-row">
                            <div class="label">Summary</div>
                            <div>${operation.summary ?? 'Keine Zusammenfassung'}</div>
                        </div>
                        <div class="kv-row">
                            <div class="label">Operation ID</div>
                            <div><code>${operation.operationId ?? 'nicht angegeben'}</code></div>
                        </div>
                        <div class="kv-row">
                            <div class="label">Parameter</div>
                            <pre>${renderValue(parameters)}</pre>
                        </div>
                        <div class="kv-row">
                            <div class="label">Responses</div>
                            <pre>${renderValue(responses)}</pre>
                        </div>
                    </div>
                `;
            };

            fetch('{{ route('openapi.json') }}')
                .then((response) => response.json())
                .then((spec) => {
                    apiTitle.textContent = spec.info.title;
                    apiVersion.textContent = `Version ${spec.info.version}`;
                    apiDescription.textContent = spec.info.description ?? '';

                    const endpoints = Object.entries(spec.paths).flatMap(([path, methods]) =>
                        Object.entries(methods).map(([method, operation]) => ({ path, method, operation })),
                    );

                    routeList.innerHTML = '';

                    endpoints.forEach((endpoint, index) => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'route-button';
                        button.innerHTML = `<span class="route-method">${endpoint.method.toUpperCase()}</span>${endpoint.path}`;

                        button.addEventListener('click', () => {
                            document.querySelectorAll('.route-button').forEach((item) => item.classList.remove('active'));
                            button.classList.add('active');
                            renderEndpoint(endpoint.path, endpoint.method, endpoint.operation);
                        });

                        routeList.appendChild(button);

                        if (index === 0) {
                            button.classList.add('active');
                            renderEndpoint(endpoint.path, endpoint.method, endpoint.operation);
                        }
                    });
                })
                .catch(() => {
                    routeList.innerHTML = '<p class="empty">Die Spezifikation konnte nicht geladen werden.</p>';
                    endpointCard.innerHTML = '<div class="empty">Fehler beim Laden der OpenAPI-Daten.</div>';
                });
        </script>
    </body>
</html>
