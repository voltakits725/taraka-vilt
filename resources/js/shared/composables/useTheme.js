import { ref } from 'vue'

// Tema Premium Cafe Taraka — Curated Collection
const THEMES = [
    {
        id: 'midnight',
        name: 'Midnight Espresso',
        description: 'Gelap elegan dengan aksen karamel hangat. Vibes cafe malam yang intim.',
        emoji: '☕',
        colors: {
            base: '#0E0B0A',
            surface: '#1A1211',
            surfaceHover: '#2C1F1B',
            accent: '#C07B54',
            textMain: '#FDF5EF',
            textMuted: '#9A8880',
            border: '#2E2320'
        }
    },
    {
        id: 'matcha',
        name: 'Matcha Oasis',
        description: 'Segar & bersih. Nuansa hijau matcha yang menenangkan untuk siang hari.',
        emoji: '🍵',
        colors: {
            base: '#F5F8F5',
            surface: '#FFFFFF',
            surfaceHover: '#EAF2EC',
            accent: '#2D6A4F',
            textMain: '#1B3A2D',
            textMuted: '#52796F',
            border: '#D8E8DC'
        }
    },
    {
        id: 'latte',
        name: 'Latte Crema',
        description: 'Hangat & cozy seperti segelas latte di pagi hari. Tone krem yang premium.',
        emoji: '🥛',
        colors: {
            base: '#FBF5EF',
            surface: '#FFFFFF',
            surfaceHover: '#F5E8DC',
            accent: '#B5622E',
            textMain: '#3D2B1F',
            textMuted: '#9A7868',
            border: '#E8D8CE'
        }
    },
    {
        id: 'coldbrewnoir',
        name: 'Cold Brew Noir',
        description: 'Ultra dark & moody. Hitam pekat yang bold seperti cold brew original.',
        emoji: '🖤',
        colors: {
            base: '#050505',
            surface: '#0F0F0F',
            surfaceHover: '#1A1A1A',
            accent: '#D4A853',
            textMain: '#F5F5F5',
            textMuted: '#707070',
            border: '#222222'
        }
    },
    {
        id: 'sakura',
        name: 'Sakura Milk',
        description: 'Lembut merah muda seperti sakura latte. Estetik & feminine.',
        emoji: '🌸',
        colors: {
            base: '#FFF0F4',
            surface: '#FFFFFF',
            surfaceHover: '#FFE0E8',
            accent: '#C2456F',
            textMain: '#4A1830',
            textMuted: '#A06080',
            border: '#F0C8D8'
        }
    },
    {
        id: 'royalblue',
        name: 'Royal Blue Reserve',
        description: 'Biru navy premium dengan aksen emas. Elegan seperti cafe bintang lima.',
        emoji: '👑',
        colors: {
            base: '#08101E',
            surface: '#111827',
            surfaceHover: '#1E2D45',
            accent: '#E8B84B',
            textMain: '#EDF2FA',
            textMuted: '#8CA0BE',
            border: '#1E2D45'
        }
    },
    {
        id: 'mocha',
        name: 'Mocha Velvet',
        description: 'Coklat tua beludru yang hangat. Classic cafe feel yang nggak pernah salah.',
        emoji: '🍫',
        colors: {
            base: '#1A1008',
            surface: '#261808',
            surfaceHover: '#3A2510',
            accent: '#E8923C',
            textMain: '#F5E8D8',
            textMuted: '#9A8060',
            border: '#3A2510'
        }
    },
    {
        id: 'mintchoc',
        name: 'Mint Chocolate',
        description: 'Hijau mint segar dipadukan coklat. Unik, playful, tapi tetap premium.',
        emoji: '🌿',
        colors: {
            base: '#0A1510',
            surface: '#101E18',
            surfaceHover: '#1A3025',
            accent: '#4ECDA4',
            textMain: '#E0F5EE',
            textMuted: '#7AB09A',
            border: '#1E3828'
        }
    },
    {
        id: 'earlgrey',
        name: 'Earl Grey Minimal',
        description: 'Abu terang yang sangat bersih & minimalis. Fokus ke konten.',
        emoji: '🫖',
        colors: {
            base: '#F4F4F5',
            surface: '#FFFFFF',
            surfaceHover: '#E8E8EC',
            accent: '#2C2C2E',
            textMain: '#18181B',
            textMuted: '#71717A',
            border: '#D4D4D8'
        }
    },
]

const currentTheme = ref(THEMES[0])

export function useTheme() {
    const applyThemeToDOM = (theme) => {
        const root = document.documentElement
        root.style.setProperty('--bg-base', theme.colors.base)
        root.style.setProperty('--bg-surface', theme.colors.surface)
        root.style.setProperty('--bg-surface-hover', theme.colors.surfaceHover)
        root.style.setProperty('--color-accent', theme.colors.accent)
        root.style.setProperty('--text-main', theme.colors.textMain)
        root.style.setProperty('--text-muted', theme.colors.textMuted)
        root.style.setProperty('--border-color', theme.colors.border)
    }

    const initTheme = () => {
        const saved = localStorage.getItem('taraka_theme')
        if (saved) {
            const found = THEMES.find(t => t.id === saved)
            if (found) {
                currentTheme.value = found
                applyThemeToDOM(found)
                return
            }
        }
        applyThemeToDOM(THEMES[0])
    }

    const setTheme = (themeId) => {
        const found = THEMES.find(t => t.id === themeId)
        if (found) {
            currentTheme.value = found
            localStorage.setItem('taraka_theme', themeId)
            applyThemeToDOM(found)
        }
    }

    return { themes: THEMES, currentTheme, initTheme, setTheme }
}