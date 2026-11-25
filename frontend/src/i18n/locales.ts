export const siteBrandName = 'RMB Sewa Motor Kuta';

export const locales = [
  {
    code: 'id',
    label: 'Indonesia',
    shortLabel: 'ID',
    flagIcon: 'flag:id-4x3',
  },
  {
    code: 'en',
    label: 'English',
    shortLabel: 'EN',
    flagIcon: 'flag:gb-4x3',
  },
] as const;

export type LocaleCode = (typeof locales)[number]['code'];
