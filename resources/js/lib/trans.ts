/** Fills the :placeholders Laravel leaves untouched when a catalog is sent to the client. */
export function format(
    line: string,
    replacements: Record<string, string | number>,
): string {
    return Object.entries(replacements).reduce(
        (text, [token, value]) => text.replace(`:${token}`, String(value)),
        line,
    );
}
