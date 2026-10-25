export const sitePath = (locale, path = '') => `/${locale}${path ? `/${path.replace(/^\/+/, '')}` : ''}`;

export const adminPath = (path = '') => `/admin${path ? `/${path.replace(/^\/+/, '')}` : ''}`;

export const imageUrl = (path) => {
    if (!path) {
        return null;
    }

    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
    }

    const clean = path.replace(/^\/+/, '');

    if (clean.startsWith('images/')) {
        return `/${clean}`;
    }

    if (clean.startsWith('storage/')) {
        return `/${clean}`;
    }

    return `/storage/${clean}`;
};

export const queryString = (values) => {
    const params = new URLSearchParams();

    Object.entries(values).forEach(([key, value]) => {
        if (value !== null && value !== undefined && value !== '') {
            params.set(key, value);
        }
    });

    return params.toString();
};
