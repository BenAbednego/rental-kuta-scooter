import { siteBrandName, type LocaleCode } from '../i18n/locales';
import { wa } from '../i18n/ui';

export interface BookingMessageData {
  motor: string;
  name: string;
  email?: string | null;
  startDate?: string;
  endDate?: string;
  duration?: number;
  phone?: string | null;
  documentType?: string | null;
  addons?: Array<{ quantity: number; name?: string; name_en?: string }> | null;
  notes?: string | null;
  total?: number;
}

export function buildBookingMessage(
  lang: LocaleCode,
  data?: BookingMessageData,
): string {
  const strings = wa[lang];
  const greeting = (text: string) => text.replace('{brand}', siteBrandName);

  if (!data) return greeting(strings.inquiry);

  const locale = lang === 'id' ? 'id-ID' : 'en-GB';
  const formatDate = (value: string) => new Intl.DateTimeFormat(locale, {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }).format(new Date(`${value.slice(0, 10)}T00:00:00`));
  const durationUnit = (data.duration ?? 1) === 1 ? strings.day : strings.days;

  if ('total' in data || 'phone' in data || 'documentType' in data) {
    const documentTypes: Record<string, string> = {
      passport: strings.passport,
      ktp: strings.ktp,
      sim: strings.sim,
    };
    const addonList = data.addons?.length
      ? data.addons.map((addon) => `${addon.quantity}x ${lang === 'en' && addon.name_en ? addon.name_en : addon.name || strings.emptyValue}`).join(', ')
      : strings.none;
    const total = new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      maximumFractionDigits: 0,
    }).format(data.total ?? 0);

    return [
      greeting(strings.legacyBookingIntro),
      '',
      `*${strings.bike}:* ${data.motor}`,
      `*${strings.name}:* ${data.name}`,
      `*${strings.phone}:* ${data.phone || strings.emptyValue}`,
      `*${strings.documentType}:* ${documentTypes[(data.documentType || '').toLowerCase()] || strings.emptyValue}`,
      ...(data.startDate ? [`*${strings.startDate}:* ${formatDate(data.startDate)}`] : []),
      ...(data.endDate ? [`*${strings.endDate}:* ${formatDate(data.endDate)}`] : []),
      ...(data.duration ? [`*${strings.duration}:* ${data.duration} ${durationUnit}`] : []),
      `*${strings.addons}:* ${addonList}`,
      `*${strings.notes}:* ${data.notes || strings.emptyValue}`,
      `*${strings.total}:* ${total}`,
      '',
      strings.confirm,
    ].join('\n');
  }

  return [
    greeting(strings.bookingIntro),
    '',
    `*${strings.bike}:* ${data.motor}`,
    `*${strings.name}:* ${data.name}`,
    `*${strings.email}:* ${data.email || strings.emptyValue}`,
    ...(data.startDate ? [`*${strings.startDate}:* ${formatDate(data.startDate)}`] : []),
    ...(data.endDate ? [`*${strings.endDate}:* ${formatDate(data.endDate)}`] : []),
    ...(data.duration ? [`*${strings.duration}:* ${data.duration} ${durationUnit}`] : []),
    '',
    strings.confirm,
  ].join('\n');
}

export function buildMotorInquiryMessage(lang: LocaleCode, motorName: string): string {
  return wa[lang].motorInquiry
    .replace('{brand}', siteBrandName)
    .replace('{motor}', motorName);
}
