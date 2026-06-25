import type { Guest } from './guests';

/**
 * Save the Date / guest pre-confirmation contract.
 *
 * Mirrors the props rendered by `pages/SaveTheDate.vue`. The reusable
 * `Guest` shape already matches the guest display contract, so group
 * members extend it with their (possibly absent) intent response.
 */

export type ResponseOption = 'yes' | 'probably_yes' | 'probably_no' | 'no';

export interface GuestGroupMember extends Guest {
    /** A previously given response, or null when not answered yet. */
    response: ResponseOption | null;
}

export interface Language {
    label: string;
    value: string;
    flag: string;
}

export interface SaveTheDateProps {
    /** The guest who opened the page — display only (greeting, etc.). */
    guest: Guest;
    /** Every member of the group, including the accessing guest. */
    guestGroup: GuestGroupMember[];
    /** Display language; defaults to the guest's own language. */
    lang: string;
    /** Languages offered by the language selector, keyed by value. */
    languages: Record<string, Language>;
    /** Response options as a value→label map, localized by the backend. */
    options: Record<ResponseOption, string>;
}

/** One entry of the confirmation payload sent back on submit. */
export interface ConfirmationItem {
    id: number;
    response: ResponseOption | null;
}
