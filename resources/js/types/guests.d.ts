export interface Guest {
    id: number;
    name: ?string;
    mobile: ?string;
    lang: 'en' | 'es' | 'pt' | null;
}
