export const API_BASE_URL = import.meta.env.PUBLIC_API_BASE_URL || 'http://127.0.0.1:8089/api';
export const DEFAULT_WHATSAPP_NUMBER = '6285947167662';

/**
 * Robust fetch that automatically falls back between port 8089 and port 8000
 * so the frontend never loses data if Laravel is running on either port.
 */
async function resilientFetch(path: string, init?: RequestInit): Promise<Response> {
  const url1 = `${API_BASE_URL}${path}`;
  try {
    const res = await fetch(url1, init);
    if (res.ok) return res;
    if (url1.includes(':8089') || url1.includes(':8000')) {
      const altBase = url1.includes(':8089')
        ? API_BASE_URL.replace(':8089', ':8000')
        : API_BASE_URL.replace(':8000', ':8089');
      const altRes = await fetch(`${altBase}${path}`, init).catch(() => null);
      if (altRes && altRes.ok) return altRes;
    }
    return res;
  } catch (err) {
    if (url1.includes(':8089') || url1.includes(':8000')) {
      const altBase = url1.includes(':8089')
        ? API_BASE_URL.replace(':8089', ':8000')
        : API_BASE_URL.replace(':8000', ':8089');
      try {
        return await fetch(`${altBase}${path}`, init);
      } catch {
        throw err;
      }
    }
    throw err;
  }
}

export interface Motor {
  id: number;
  name: string;
  slug: string;
  brand?: string | null;
  category: 'matic' | 'premium' | 'sport' | string;
  cc: number | null;
  description_id: string | null;
  description_en: string | null;
  price_daily: number;
  price_weekly: number | null;
  price_monthly: number | null;
  deposit_amount: number | null;
  unit_count: number;
  main_image: string | null;
  gallery_images: string[];
  status: string;
  specs: Record<string, string>;
  created_at?: string;
}

export interface HeroBackground {
  type: 'default' | 'single_image' | 'slider' | 'video';
  image_url: string | null;
  slides: string[] | null;
  slide_interval: number | null;
  video: {
    source: 'upload' | 'youtube';
    url: string | null;
    youtube_id: string | null;
  } | null;
  poster_url: string | null;
}

const DEFAULT_HERO_BACKGROUND: HeroBackground = {
  type: 'default',
  image_url: null,
  slides: null,
  slide_interval: null,
  video: null,
  poster_url: null,
};

export async function fetchHero(): Promise<HeroBackground> {
  try {
    const res = await resilientFetch('/hero');
    if (!res.ok) return DEFAULT_HERO_BACKGROUND;

    const json = await res.json();
    const hero = json as Partial<HeroBackground>;

    if (hero.type === 'single_image' && hero.image_url) {
      return { ...DEFAULT_HERO_BACKGROUND, ...hero };
    }
    if (hero.type === 'slider' && Array.isArray(hero.slides) && hero.slides.length >= 2) {
      return { ...DEFAULT_HERO_BACKGROUND, ...hero };
    }
    if (hero.type === 'video' && hero.video?.source === 'upload' && hero.video.url) {
      return { ...DEFAULT_HERO_BACKGROUND, ...hero };
    }
    if (
      hero.type === 'video'
      && hero.video?.source === 'youtube'
      && typeof hero.video.youtube_id === 'string'
      && /^[A-Za-z0-9_-]{11}$/.test(hero.video.youtube_id)
    ) {
      return { ...DEFAULT_HERO_BACKGROUND, ...hero };
    }

    return DEFAULT_HERO_BACKGROUND;
  } catch (error) {
    console.error('Error fetching hero settings:', error);
    return DEFAULT_HERO_BACKGROUND;
  }
}

export interface BookingAddonItem {
  id: number;
  name: string;
  name_en?: string;
  price: number;
  price_type: string;
  quantity: number;
  subtotal: number;
}

export interface BookingResponseData {
  id: number;
  booking_id: number;
  motor: {
    id: number;
    name: string;
    main_image: string | null;
  };
  customer_name: string;
  customer_email?: string | null;
  customer_whatsapp: string | null;
  customer_document_type?: string | null;
  start_date: string;
  end_date: string;
  total_days: number;
  total_price: number;
  addons?: BookingAddonItem[];
  addons_total?: number;
  status: string;
  notes?: string | null;
  whatsapp_url?: string;
  created_at: string;
}

export interface Addon {
  id: number;
  name_id: string;
  name_en: string;
  description_id: string | null;
  description_en: string | null;
  price: number;
  price_type: 'per_day' | 'per_rental' | string;
  sort_order: number;
}

export async function fetchMotors(category?: string): Promise<Motor[]> {
  try {
    const query = category ? `?category=${encodeURIComponent(category)}` : '';
    const res = await resilientFetch(`/motors${query}`);
    if (!res.ok) return [];

    const json = await res.json();
    return Array.isArray(json?.data) ? json.data : [];
  } catch (error) {
    console.error('Error fetching motors:', error);
    return [];
  }
}

export async function fetchMotorBySlug(slug: string): Promise<Motor | null> {
  try {
    const res = await resilientFetch(`/motors/${slug}`);
    if (!res.ok) return null;

    const json = await res.json();
    return json?.data ?? null;
  } catch (error) {
    console.error(`Error fetching motor slug ${slug}:`, error);
    return null;
  }
}

export async function fetchMotorById(id: number | string): Promise<Motor | null> {
  try {
    const motors = await fetchMotors();
    return motors.find(m => m.id === Number(id)) || null;
  } catch (error) {
    console.error(`Error fetching motor id ${id}:`, error);
    return null;
  }
}

export async function fetchBookingById(id: number | string): Promise<BookingResponseData | null> {
  try {
    const res = await resilientFetch(`/bookings/${id}`);
    if (!res.ok) return null;

    const json = await res.json();
    return json?.data ?? null;
  } catch (error) {
    console.error(`Error fetching booking #${id}:`, error);
    return null;
  }
}

export const FALLBACK_ADDONS: Addon[] = [
  {
    id: 1,
    name_id: "Helm Tambahan (SNI)",
    name_en: "Additional Helmet (SNI)",
    description_id: "Helm SNI bersih, higienis, dan wangi untuk kenyamanan berkendara berdua.",
    description_en: "Clean, sanitized, and fresh SNI standard helmet for passenger comfort.",
    price: 10000,
    price_type: "per_rental",
    sort_order: 1
  },
  {
    id: 2,
    name_id: "Jas Hujan Tambahan",
    name_en: "Additional Raincoat",
    description_id: "Setelan jas hujan berkualitas tebal dan nyaman saat cuaca hujan di Bali.",
    description_en: "Quality two-piece waterproof raincoat set for unexpected rainy weather.",
    price: 10000,
    price_type: "per_rental",
    sort_order: 2
  },
  {
    id: 3,
    name_id: "Phone Holder Stang",
    name_en: "Handlebar Phone Holder",
    description_id: "Dudukan smartphone kokoh dan aman untuk kemudahan navigasi peta rute.",
    description_en: "Sturdy and secure smartphone holder for GPS and maps navigation.",
    price: 5000,
    price_type: "per_rental",
    sort_order: 3
  },
  {
    id: 4,
    name_id: "Surf Rack (Rak Papan Selancar)",
    name_en: "Surfboard Rack",
    description_id: "Rak samping berlapis busa empuk untuk membawa papan surfing ke pantai.",
    description_en: "Padded side rack to safely transport your surfboard to Bali beach breaks.",
    price: 25000,
    price_type: "per_rental",
    sort_order: 4
  }
];

export async function fetchAddons(): Promise<Addon[]> {
  try {
    const res = await resilientFetch('/addons');
    if (!res.ok) return FALLBACK_ADDONS;

    const json = await res.json();
    return Array.isArray(json?.data) && json.data.length > 0 ? json.data : FALLBACK_ADDONS;
  } catch (error) {
    console.error("Error fetching addons:", error);
    return FALLBACK_ADDONS;
  }
}

export async function submitBooking(payload: {
  motor_id: number;
  customer_name: string;
  customer_whatsapp: string;
  customer_document_type?: string;
  start_date: string;
  end_date: string;
  notes?: string;
  addons?: Array<{ addon_id: number; quantity: number }>;
}): Promise<{ success: boolean; message?: string; data?: BookingResponseData }> {
  try {
    const res = await resilientFetch('/bookings', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    const json = await res.json();
    if (!res.ok) {
      return {
        success: false,
        message: json?.message || 'Gagal menyimpan booking.',
      };
    }

    return {
      success: true,
      data: json?.data,
    };
  } catch (error) {
    console.error('Error submitting booking:', error);
    return {
      success: false,
      message: 'Terjadi kesalahan jaringan.',
    };
  }
}


export interface FaqItem {
  id: number;
  question_id: string;
  answer_id: string;
  question_en: string;
  answer_en: string;
  order: number;
  is_active: boolean;
}

export interface GalleryItem {
  id: number;
  image_path: string;
  image_url: string;
  caption: string | null;
  order: number;
  is_active: boolean;
}

export async function fetchFaqs(): Promise<FaqItem[]> {
  try {
    const res = await resilientFetch('/faqs');
    if (!res.ok) return [];

    const json = await res.json();
    return Array.isArray(json?.data) ? json.data : [];
  } catch (error) {
    console.error("Error fetching faqs:", error);
    return [];
  }
}

export async function fetchGallery(): Promise<GalleryItem[]> {
  try {
    const res = await resilientFetch('/gallery');
    if (!res.ok) return [];

    const json = await res.json();
    return Array.isArray(json?.data) ? json.data : [];
  } catch (error) {
    console.error("Error fetching gallery:", error);
    return [];
  }
}

export interface PricingPackageItem {
  id: number;
  name_id: string;
  name_en: string;
  subtitle_id: string | null;
  subtitle_en: string | null;
  price: number | string;
  price_unit_id: string;
  price_unit_en: string;
  benefits_id: string[];
  benefits_en: string[];
  icon: string;
  cta_label_id: string;
  cta_label_en: string;
  is_featured: boolean;
  order: number;
  is_active: boolean;
}

export async function fetchPricingPackages(): Promise<PricingPackageItem[]> {
  try {
    const res = await resilientFetch('/pricing-packages');
    if (!res.ok) return [];

    const json = await res.json();
    return Array.isArray(json?.data) ? json.data : [];
  } catch (error) {
    console.error("Error fetching pricing packages:", error);
    return [];
  }
}

export interface TestimonialItem {
  id: number;
  name: string;
  location: string;
  text_id: string;
  text_en: string;
  rating: number;
  order: number;
}

export const FALLBACK_TESTIMONIALS: TestimonialItem[] = [
  {
    id: 1,
    name: 'Budi Santoso',
    location: 'Jakarta',
    text_id: 'Pelayanan cepat banget! Motor Vario diantar ke hotel Kuta tepat waktu. Helm bersih & wangi, kondisi fisik motor sangat terawat.',
    text_en: 'Very fast service! The Vario was delivered to our Kuta hotel right on time. Helmets were clean, and the bike was in excellent condition.',
    rating: 5,
    order: 1,
  },
  {
    id: 2,
    name: 'Sarah Jenkins',
    location: 'Australia',
    text_id: 'Sewa NMAX selama 5 hari. Proses mudah via WhatsApp, staf ramah, tanpa biaya tersembunyi. Pasti sewa di sini lagi saat ke Bali.',
    text_en: 'Rented an NMAX for 5 days. Smooth process via WhatsApp, friendly team, no hidden fees. Will definitely rent here again on my next Bali trip.',
    rating: 5,
    order: 2,
  },
  {
    id: 3,
    name: 'Michael Chen',
    location: 'Singapore',
    text_id: 'Serah terima sangat cepat di Bandara Ngurah Rai. Motor berjalan mulus sepanjang perjalanan, dan deposit dikembalikan tepat waktu.',
    text_en: 'Very fast airport drop-off at Ngurah Rai. The scooter ran smoothly throughout our entire trip. Transparent deposit policy.',
    rating: 5,
    order: 3,
  },
];

export async function fetchTestimonials(): Promise<TestimonialItem[]> {
  try {
    const res = await resilientFetch('/testimonials');
    if (!res.ok) return FALLBACK_TESTIMONIALS;

    const json = await res.json();
    return Array.isArray(json?.data) ? json.data : FALLBACK_TESTIMONIALS;
  } catch (error) {
    console.error('Error fetching testimonials:', error);
    return FALLBACK_TESTIMONIALS;
  }
}

export interface ReviewIframeSettings {
  src: string;
  title: string | null;
  height: number | null;
  provider_label: string;
}

export interface ReviewSettings {
  mode: 'manual' | 'iframe';
  iframe?: ReviewIframeSettings;
}

export type SeoPageKey = 'home' | 'fleet' | 'contact' | 'about';

export interface PageSeoData {
  id: {
    title: string | null;
    description: string | null;
  };
  en: {
    title: string | null;
    description: string | null;
  };
  image_url: string | null;
}

const EMPTY_PAGE_SEO: PageSeoData = {
  id: { title: null, description: null },
  en: { title: null, description: null },
  image_url: null,
};

export async function fetchSeo(pageKey: SeoPageKey): Promise<PageSeoData> {
  try {
    const res = await resilientFetch(`/seo/${encodeURIComponent(pageKey)}`);
    if (!res.ok) return EMPTY_PAGE_SEO;

    const json = await res.json();
    return {
      id: {
        title: typeof json?.id?.title === 'string' && json.id.title !== '' ? json.id.title : null,
        description: typeof json?.id?.description === 'string' && json.id.description !== '' ? json.id.description : null,
      },
      en: {
        title: typeof json?.en?.title === 'string' && json.en.title !== '' ? json.en.title : null,
        description: typeof json?.en?.description === 'string' && json.en.description !== '' ? json.en.description : null,
      },
      image_url: typeof json?.image_url === 'string' && json.image_url !== '' ? json.image_url : null,
    };
  } catch (error) {
    console.error(`Error fetching SEO settings for ${pageKey}:`, error);
    return EMPTY_PAGE_SEO;
  }
}

const DEFAULT_REVIEW_SETTINGS: ReviewSettings = { mode: 'manual' };

export async function fetchReviewSettings(): Promise<ReviewSettings> {
  try {
    const res = await resilientFetch('/review-settings');
    if (!res.ok) return DEFAULT_REVIEW_SETTINGS;

    const json = await res.json();
    if (
      json?.mode !== 'iframe'
      || typeof json.iframe?.src !== 'string'
      || !json.iframe.src.startsWith('https://')
      || typeof json.iframe.provider_label !== 'string'
    ) {
      return DEFAULT_REVIEW_SETTINGS;
    }

    return {
      mode: 'iframe',
      iframe: {
        src: json.iframe.src,
        title: typeof json.iframe.title === 'string' ? json.iframe.title : null,
        height: Number.isInteger(json.iframe.height) ? json.iframe.height : null,
        provider_label: json.iframe.provider_label,
      },
    };
  } catch (error) {
    console.error('Error fetching review settings:', error);
    return DEFAULT_REVIEW_SETTINGS;
  }
}

export interface BusinessSettings {
  id: number;
  whatsapp_primary: string;
  whatsapp_secondary: string | null;
  business_email: string | null;
  business_address: string | null;
  operating_hours_id: string;
  operating_hours_en: string;
  instagram_url: string | null;
}

export const FALLBACK_SETTINGS: BusinessSettings = {
  id: 1,
  whatsapp_primary: '6285947167662',
  whatsapp_secondary: null,
  business_email: 'info@sewamotorkuta.id',
  business_address: 'Jl. Pantai Kuta No. 88, Badung, Bali 80361',
  operating_hours_id: 'Setiap Hari (Senin - Minggu): 08:00 - 22:00 WITA',
  operating_hours_en: 'Everyday (Monday - Sunday): 08:00 - 22:00 WITA',
  instagram_url: 'https://instagram.com/rmb_kutabali',
};

export async function fetchSettings(): Promise<BusinessSettings> {
  try {
    const res = await resilientFetch('/settings');
    if (!res.ok) return FALLBACK_SETTINGS;

    const json = await res.json();
    if (json?.data && json.data.whatsapp_primary) {
      return {
        ...FALLBACK_SETTINGS,
        ...json.data,
      };
    }
    return FALLBACK_SETTINGS;
  } catch (error) {
    console.error("Error fetching business settings:", error);
    return FALLBACK_SETTINGS;
  }
}

/**
 * Format raw WhatsApp/Phone string for user-facing display
 * Example: '6285947167662' -> '0859-4716-7662' (or '+62 859-4716-7662')
 */
export function formatPhoneNumber(phone: string, withPlus = false): string {
  if (!phone) return '';
  const clean = phone.replace(/\D/g, '');
  if (clean.startsWith('62')) {
    if (withPlus) {
      const rest = clean.slice(2);
      if (rest.length >= 8) {
        return `+62 ${rest.slice(0, 3)}-${rest.slice(3, 7)}-${rest.slice(7)}`;
      }
      return `+62 ${rest}`;
    }
    const local = '0' + clean.slice(2);
    if (local.length >= 10) {
      return `${local.slice(0, 4)}-${local.slice(4, 8)}-${local.slice(8)}`;
    }
    return local;
  }
  return phone;
}

/**
 * Sanitize any phone number into clean E.164 digits without '+' for wa.me links
 * Handles: '082380753577' -> '6282380753577'
 *          '+62 859-4716-7662' -> '6285947167662'
 *          '6285947167662' -> '6285947167662'
 */
export function sanitizeWaNumber(phone: string): string {
  if (!phone) return DEFAULT_WHATSAPP_NUMBER;
  let clean = phone.replace(/\D/g, '');
  if (clean.startsWith('0')) {
    clean = '62' + clean.slice(1);
  } else if (clean.startsWith('8')) {
    clean = '62' + clean;
  }
  return clean || DEFAULT_WHATSAPP_NUMBER;
}
