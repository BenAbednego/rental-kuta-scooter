import { ui, defaultLang, languages, type TranslationKey } from './ui';

export function getLangFromUrl(url: URL): keyof typeof ui {
  const [, lang] = url.pathname.split('/');
  if (lang in ui) return lang as keyof typeof ui;
  return defaultLang;
}

export function useTranslations(lang: keyof typeof ui) {
  return function t(key: TranslationKey): string {
    return ui[lang][key] || ui[defaultLang][key] || key;
  };
}

export function useTranslatedPath(lang: keyof typeof ui) {
  return function translatePath(path: string, l: string = lang) {
    const cleanPath = path.startsWith('/') ? path : `/${path}`;
    return `/${l}${cleanPath}`;
  };
}

export function getRouteWithoutLang(pathname: string): string {
  const segments = pathname.split('/').filter(Boolean);
  if (segments[0] === 'id' || segments[0] === 'en') {
    return '/' + segments.slice(1).join('/');
  }
  return pathname;
}
