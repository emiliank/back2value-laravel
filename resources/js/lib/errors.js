export const fieldError = (errors, key) => {
    const error = errors?.[key];

    if (Array.isArray(error)) {
        return error[0];
    }

    return typeof error === 'string' ? error : null;
};

export const errorList = (errors) => Object.values(errors ?? {})
    .flatMap((error) => (Array.isArray(error) ? error : [error]))
    .filter(Boolean);
