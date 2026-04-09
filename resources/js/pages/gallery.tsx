import { Head } from '@inertiajs/react';
import { useEffect, useState } from 'react';

type GalleryImage = {
    title: string;
    image_url: string;
    sort_order: number;
    alt_text: string | null;
    is_active: boolean;
};

type GalleryResponse = {
    data: GalleryImage[];
};

export default function Gallery({
    galleryEndpoint,
    pageTitle,
}: {
    galleryEndpoint: string;
    pageTitle: string;
}) {
    const [images, setImages] = useState<GalleryImage[]>([]);
    const [isLoading, setIsLoading] = useState(true);
    const [errorMessage, setErrorMessage] = useState<string | null>(null);

    useEffect(() => {
        const abortController = new AbortController();

        const loadGallery = async (): Promise<void> => {
            try {
                setIsLoading(true);
                setErrorMessage(null);

                const response = await fetch(galleryEndpoint, {
                    headers: {
                        Accept: 'application/json',
                    },
                    signal: abortController.signal,
                });

                if (!response.ok) {
                    throw new Error(
                        `Gallery request failed with status ${response.status}`,
                    );
                }

                const payload = (await response.json()) as GalleryResponse;

                setImages(Array.isArray(payload.data) ? payload.data : []);
            } catch (error) {
                if (
                    error instanceof DOMException &&
                    error.name === 'AbortError'
                ) {
                    return;
                }

                setImages([]);
                setErrorMessage(
                    'Die Galerie konnte aktuell nicht geladen werden. Bitte versuchen Sie es spaeter erneut.',
                );
            } finally {
                setIsLoading(false);
            }
        };

        void loadGallery();

        return () => {
            abortController.abort();
        };
    }, [galleryEndpoint]);

    return (
        <>
            <Head title={pageTitle} />

            <div className="min-h-screen bg-[linear-gradient(180deg,#f7f0e5_0%,#fbf8f3_48%,#ffffff_100%)] text-stone-900">
                <div className="mx-auto flex w-full max-w-7xl flex-col gap-12 px-6 py-10 sm:px-8 lg:px-12 lg:py-16">
                    <header className="overflow-hidden rounded-[2rem] border border-stone-200/80 bg-white/85 p-8 shadow-[0_24px_80px_-36px_rgba(120,53,15,0.38)] backdrop-blur md:p-12">
                        <div className="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                            <div className="max-w-3xl space-y-4">
                                <span className="inline-flex w-fit rounded-full border border-amber-300 bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-amber-900">
                                    API-gestuetzte Galerie
                                </span>
                                <div className="space-y-3">
                                    <h1 className="font-serif text-4xl tracking-tight text-stone-950 sm:text-5xl lg:text-6xl">
                                        Eindruecke aus dem Vereinsleben
                                    </h1>
                                    <p className="max-w-2xl text-base leading-7 text-stone-600 sm:text-lg">
                                        Diese Seite laedt alle Bilder ausschliesslich
                                        ueber den oeffentlichen Galerie-Endpunkt. Pflege,
                                        Sortierung und Sichtbarkeit erfolgen im Admin
                                        Panel.
                                    </p>
                                </div>
                            </div>

                            <div className="grid gap-3 rounded-3xl bg-stone-950 px-5 py-4 text-sm text-stone-100 sm:grid-cols-2 sm:gap-6">
                                <div>
                                    <p className="text-stone-400">Quelle</p>
                                    <p className="mt-1 font-medium">{galleryEndpoint}</p>
                                </div>
                                <div>
                                    <p className="text-stone-400">Status</p>
                                    <p className="mt-1 font-medium">
                                        {isLoading
                                            ? 'Laedt'
                                            : errorMessage
                                              ? 'Fehler'
                                              : `${images.length} Bilder`}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </header>

                    {errorMessage ? (
                        <section className="rounded-[1.75rem] border border-red-200 bg-red-50 p-6 text-red-900 shadow-sm">
                            <h2 className="text-lg font-semibold">
                                Galerie derzeit nicht verfuegbar
                            </h2>
                            <p className="mt-2 max-w-2xl text-sm leading-6 text-red-800">
                                {errorMessage}
                            </p>
                        </section>
                    ) : null}

                    <main className="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        {isLoading
                            ? Array.from({ length: 6 }, (_, index) => (
                                  <article
                                      key={`gallery-skeleton-${index}`}
                                      className="overflow-hidden rounded-[1.75rem] border border-stone-200 bg-white shadow-[0_20px_60px_-32px_rgba(28,25,23,0.32)]"
                                  >
                                      <div className="aspect-[4/5] animate-pulse bg-stone-200" />
                                      <div className="space-y-3 p-5">
                                          <div className="h-4 w-3/4 animate-pulse rounded-full bg-stone-200" />
                                          <div className="h-3 w-1/2 animate-pulse rounded-full bg-stone-100" />
                                      </div>
                                  </article>
                              ))
                            : images.map((image) => (
                                  <article
                                      key={`${image.sort_order}-${image.image_url}`}
                                      className="group overflow-hidden rounded-[1.75rem] border border-stone-200/80 bg-white shadow-[0_20px_60px_-32px_rgba(28,25,23,0.32)] transition-transform duration-300 hover:-translate-y-1"
                                  >
                                      <div className="relative aspect-[4/5] overflow-hidden bg-stone-100">
                                          <img
                                              src={image.image_url}
                                              alt={image.alt_text ?? image.title}
                                              className="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                              loading="lazy"
                                          />
                                          <div className="absolute inset-x-0 bottom-0 bg-linear-to-t from-stone-950 via-stone-950/55 to-transparent px-5 py-5 text-stone-50">
                                              <h2 className="text-xl font-semibold tracking-tight">
                                                  {image.title}
                                              </h2>
                                              <p className="mt-1 text-sm text-stone-200">
                                                  Bild #{image.sort_order}
                                              </p>
                                          </div>
                                      </div>

                                      {image.alt_text ? (
                                          <div className="border-t border-stone-100 px-5 py-4 text-sm leading-6 text-stone-600">
                                              {image.alt_text}
                                          </div>
                                      ) : null}
                                  </article>
                              ))}
                    </main>

                    {!isLoading && !errorMessage && images.length === 0 ? (
                        <section className="rounded-[1.75rem] border border-dashed border-stone-300 bg-white/90 p-10 text-center shadow-sm">
                            <h2 className="text-xl font-semibold text-stone-900">
                                Noch keine Bilder sichtbar
                            </h2>
                            <p className="mt-3 text-sm leading-6 text-stone-600">
                                Sobald im Admin Panel aktive Galerie-Bilder gepflegt
                                sind, erscheinen sie hier automatisch ueber die API.
                            </p>
                        </section>
                    ) : null}
                </div>
            </div>
        </>
    );
}
