import { Head } from '@inertiajs/react';
import { startTransition, useEffect, useState } from 'react';

type ContactCard = {
    title: string | null;
    description: string | null;
    icon: string | null;
    url: string | null;
    linkLabel: string | null;
    linkKey: string | null;
    sortOrder: number;
};

type OfficialLink = {
    title: string | null;
    description: string | null;
    url: string | null;
    linkLabel: string | null;
    linkKey: string | null;
    sortOrder: number;
};

type Address = {
    title: string | null;
    description: string | null;
    name: string | null;
    street: string | null;
    postalCode: string | null;
    city: string | null;
    country: string | null;
    notes: string | null;
    lines: string[];
    sortOrder: number;
} | null;

type Notice = {
    title: string | null;
    content: string | null;
    tone: string | null;
    sortOrder: number;
};

type ContactContent = {
    title: string;
    hero: {
        eyebrow: string | null;
        title: string;
        subtitle: string | null;
        description: string | null;
    };
    intro: string | null;
    introTitle: string | null;
    contactCards: ContactCard[];
    officialLinks: OfficialLink[];
    address: Address;
    notes: Notice[];
    contactUrl: string | null;
    meta: {
        metaTitle: string | null;
        metaDescription: string | null;
    };
};

type ContactResponse = {
    data: ContactContent;
};

type DisplayCard = {
    title: string;
    description: string | null;
    lines: string[];
    url: string | null;
    linkLabel: string | null;
    key: string;
    tone: 'default' | 'notice';
};

export default function Contact({
    contentEndpoint,
    pageTitle,
}: {
    contentEndpoint: string;
    pageTitle: string;
}) {
    const [content, setContent] = useState<ContactContent | null>(null);
    const [isLoading, setIsLoading] = useState(true);
    const [errorMessage, setErrorMessage] = useState<string | null>(null);

    useEffect(() => {
        const abortController = new AbortController();

        const loadContactPage = async (): Promise<void> => {
            try {
                setIsLoading(true);
                setErrorMessage(null);

                const response = await fetch(contentEndpoint, {
                    headers: {
                        Accept: 'application/json',
                    },
                    signal: abortController.signal,
                });

                if (!response.ok) {
                    throw new Error(
                        `Contact request failed with status ${response.status}`,
                    );
                }

                const payload = (await response.json()) as ContactResponse;

                startTransition(() => {
                    setContent(payload.data ?? null);
                    setErrorMessage(null);
                });
            } catch (error) {
                if (
                    error instanceof DOMException &&
                    error.name === 'AbortError'
                ) {
                    return;
                }

                startTransition(() => {
                    setContent(null);
                    setErrorMessage(
                        'Die Kontaktseite konnte aktuell nicht geladen werden. Bitte versuchen Sie es spaeter erneut.',
                    );
                });
            } finally {
                setIsLoading(false);
            }
        };

        void loadContactPage();

        return () => {
            abortController.abort();
        };
    }, [contentEndpoint]);

    const cards = buildDisplayCards(content);
    const resolvedPageTitle = content?.meta.metaTitle ?? content?.title ?? pageTitle;

    return (
        <>
            <Head title={resolvedPageTitle}>
                {content?.meta.metaDescription ? (
                    <meta
                        name="description"
                        content={content.meta.metaDescription}
                    />
                ) : null}
            </Head>

            <div className="min-h-screen bg-[radial-gradient(circle_at_top,#f7e6c6_0%,#f8f4ed_32%,#fffdf8_70%,#ffffff_100%)] text-stone-900">
                <div className="mx-auto flex w-full max-w-7xl flex-col gap-10 px-6 py-10 sm:px-8 lg:px-12 lg:py-16">
                    <header className="overflow-hidden rounded-[2rem] border border-amber-200/70 bg-white/90 p-8 shadow-[0_28px_90px_-40px_rgba(120,53,15,0.42)] backdrop-blur md:p-12">
                        <div className="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
                            <div className="max-w-3xl space-y-5">
                                {content?.hero.eyebrow ? (
                                    <span className="inline-flex w-fit rounded-full border border-amber-300 bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-amber-950">
                                        {content.hero.eyebrow}
                                    </span>
                                ) : null}

                                <div className="space-y-3">
                                    <h1 className="font-serif text-4xl tracking-tight text-stone-950 sm:text-5xl lg:text-6xl">
                                        {content?.hero.title ?? pageTitle}
                                    </h1>
                                    {content?.hero.description ? (
                                        <p className="max-w-2xl text-base leading-7 text-stone-600 sm:text-lg">
                                            {content.hero.description}
                                        </p>
                                    ) : null}
                                </div>
                            </div>

                            <div className="flex min-w-full flex-col gap-3 rounded-[1.5rem] bg-stone-950 p-5 text-stone-100 sm:min-w-[18rem] lg:max-w-sm">
                                <p className="text-xs font-semibold uppercase tracking-[0.22em] text-stone-400">
                                    Offizieller Kontakt
                                </p>
                                <p className="text-sm leading-6 text-stone-300">
                                    Die sichtbaren Kontaktinformationen werden direkt
                                    aus dem Laravel-Backend geladen.
                                </p>
                                {content?.contactUrl ? (
                                    <a
                                        href={content.contactUrl}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="inline-flex w-fit items-center rounded-full bg-amber-300 px-4 py-2 text-sm font-semibold text-stone-950 transition hover:bg-amber-200"
                                    >
                                        Offizielle Kontaktseite
                                    </a>
                                ) : null}
                            </div>
                        </div>
                    </header>

                    {errorMessage ? (
                        <section className="rounded-[1.75rem] border border-red-200 bg-red-50 p-6 text-red-900 shadow-sm">
                            <h2 className="text-lg font-semibold">
                                Kontaktseite derzeit nicht verfuegbar
                            </h2>
                            <p className="mt-2 max-w-2xl text-sm leading-6 text-red-800">
                                {errorMessage}
                            </p>
                        </section>
                    ) : null}

                    {content?.intro ? (
                        <section className="grid gap-6 rounded-[1.75rem] border border-stone-200/80 bg-white/90 p-8 shadow-[0_18px_50px_-34px_rgba(28,25,23,0.32)] lg:grid-cols-[minmax(0,14rem)_1fr]">
                            <div>
                                <p className="text-sm font-semibold uppercase tracking-[0.22em] text-amber-900">
                                    {content.introTitle ?? 'Einleitung'}
                                </p>
                            </div>
                            <div className="max-w-3xl text-base leading-7 text-stone-600">
                                {content.intro}
                            </div>
                        </section>
                    ) : null}

                    <main className="grid gap-6 lg:grid-cols-3">
                        {isLoading
                            ? Array.from({ length: 3 }, (_, index) => (
                                  <article
                                      key={`contact-skeleton-${index}`}
                                      className="rounded-[1.75rem] border border-stone-200 bg-white p-6 shadow-[0_20px_60px_-32px_rgba(28,25,23,0.28)]"
                                  >
                                      <div className="h-4 w-24 animate-pulse rounded-full bg-stone-200" />
                                      <div className="mt-5 h-8 w-2/3 animate-pulse rounded-full bg-stone-200" />
                                      <div className="mt-6 space-y-3">
                                          <div className="h-3 animate-pulse rounded-full bg-stone-100" />
                                          <div className="h-3 w-11/12 animate-pulse rounded-full bg-stone-100" />
                                          <div className="h-3 w-4/5 animate-pulse rounded-full bg-stone-100" />
                                      </div>
                                  </article>
                              ))
                            : cards.map((card) => (
                                  <article
                                      key={card.key}
                                      className={`flex h-full flex-col rounded-[1.75rem] border p-6 shadow-[0_20px_60px_-32px_rgba(28,25,23,0.28)] ${
                                          card.tone === 'notice'
                                              ? 'border-amber-300/80 bg-amber-50'
                                              : 'border-stone-200/80 bg-white'
                                      }`}
                                  >
                                      <h2 className="text-xl font-semibold tracking-tight text-stone-950">
                                          {card.title}
                                      </h2>

                                      {card.description ? (
                                          <p className="mt-4 text-sm leading-6 text-stone-600">
                                              {card.description}
                                          </p>
                                      ) : null}

                                      {card.lines.length > 0 ? (
                                          <div className="mt-5 space-y-1 text-sm leading-6 text-stone-700">
                                              {card.lines.map((line) => (
                                                  <p key={`${card.key}-${line}`}>
                                                      {line}
                                                  </p>
                                              ))}
                                          </div>
                                      ) : null}

                                      {card.url && card.linkLabel ? (
                                          <div className="mt-auto pt-6">
                                              <a
                                                  href={card.url}
                                                  target="_blank"
                                                  rel="noreferrer"
                                                  className="inline-flex items-center rounded-full border border-stone-300 px-4 py-2 text-sm font-semibold text-stone-900 transition hover:border-stone-950 hover:bg-stone-950 hover:text-white"
                                              >
                                                  {card.linkLabel}
                                              </a>
                                          </div>
                                      ) : null}
                                  </article>
                              ))}
                    </main>

                    {!isLoading &&
                    !errorMessage &&
                    cards.length === 0 &&
                    (content?.officialLinks.length ?? 0) === 0 ? (
                        <section className="rounded-[1.75rem] border border-dashed border-stone-300 bg-white/90 p-10 text-center shadow-sm">
                            <h2 className="text-xl font-semibold text-stone-900">
                                Noch keine Kontaktinhalte gepflegt
                            </h2>
                            <p className="mt-3 text-sm leading-6 text-stone-600">
                                Sobald die Seite im Backend mit Sections und Links
                                befuellt ist, erscheinen die Inhalte hier automatisch.
                            </p>
                        </section>
                    ) : null}

                    {content && content.officialLinks.length > 0 ? (
                        <section className="rounded-[1.75rem] border border-stone-200/80 bg-stone-950 p-8 text-stone-100 shadow-[0_24px_80px_-36px_rgba(28,25,23,0.55)]">
                            <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                <div className="space-y-2">
                                    <p className="text-xs font-semibold uppercase tracking-[0.24em] text-amber-300">
                                        Offizielle Links
                                    </p>
                                    <h2 className="font-serif text-3xl tracking-tight">
                                        Zentrale Verweise aus dem Backend
                                    </h2>
                                </div>
                            </div>

                            <div className="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                {content.officialLinks.map((link) =>
                                    link.url ? (
                                        <a
                                            key={`${link.sortOrder}-${link.linkKey ?? link.url ?? link.title}`}
                                            href={link.url}
                                            target="_blank"
                                            rel="noreferrer"
                                            className="rounded-[1.5rem] border border-white/10 bg-white/5 p-5 transition hover:border-amber-300/60 hover:bg-white/10"
                                        >
                                            <p className="text-lg font-semibold text-white">
                                                {link.title ?? link.linkLabel ?? 'Externer Link'}
                                            </p>
                                            {link.description ? (
                                                <p className="mt-3 text-sm leading-6 text-stone-300">
                                                    {link.description}
                                                </p>
                                            ) : null}
                                            <p className="mt-5 text-sm font-semibold text-amber-300">
                                                {link.linkLabel ?? 'Link oeffnen'}
                                            </p>
                                        </a>
                                    ) : (
                                        <article
                                            key={`${link.sortOrder}-${link.linkKey ?? link.title ?? 'link'}`}
                                            className="rounded-[1.5rem] border border-white/10 bg-white/5 p-5"
                                        >
                                            <p className="text-lg font-semibold text-white">
                                                {link.title ?? link.linkLabel ?? 'Externer Link'}
                                            </p>
                                            {link.description ? (
                                                <p className="mt-3 text-sm leading-6 text-stone-300">
                                                    {link.description}
                                                </p>
                                            ) : null}
                                        </article>
                                    ),
                                )}
                            </div>
                        </section>
                    ) : null}
                </div>
            </div>
        </>
    );
}

function buildDisplayCards(content: ContactContent | null): DisplayCard[] {
    if (content === null) {
        return [];
    }

    const cards: DisplayCard[] = content.contactCards.map((card) => ({
        title: card.title ?? 'Kontakt',
        description: card.description,
        lines: [],
        url: card.url,
        linkLabel: card.linkLabel,
        key: `card-${card.sortOrder}-${card.linkKey ?? card.title ?? 'item'}`,
        tone: 'default',
    }));

    if (content.address) {
        cards.push({
            title: content.address.title ?? 'Anschrift',
            description: content.address.description,
            lines: [...content.address.lines, ...(content.address.notes ? [content.address.notes] : [])],
            url: null,
            linkLabel: null,
            key: `address-${content.address.sortOrder}`,
            tone: 'default',
        });
    }

    cards.push(
        ...content.notes.map((note) => ({
            title: note.title ?? 'Hinweis',
            description: note.content,
            lines: [],
            url: null,
            linkLabel: null,
            key: `note-${note.sortOrder}-${note.title ?? 'item'}`,
            tone: 'notice' as const,
        })),
    );

    return cards;
}
