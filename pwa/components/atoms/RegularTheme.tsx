import { createTheme } from '@material-ui/core/styles'

declare module '@material-ui/core/styles/createPalette' {
  interface Palette {
    neutral: Palette['primary']
  }
  interface PaletteOptions {
    neutral: PaletteOptions['primary']
  }
}

/* Creation of custom light theme
 * There is always scss assets to scss modifications but here is to be used by Material UI
 * see : https://mui.com/customization/theming/
 */
const regularTheme = createTheme({
  palette: {
    primary: {
      light: '#FBC0B9',
      main: '#ED7465',
      dark: '#A02213',
    },
    secondary: {
      light: '#BABDFC',
      main: '#2C19CD',
      dark: '#1812A0',
    },
    neutral: {
      light: '#F4F7FF',
      main: '#B5B9D9',
      dark: '#2F3674',
    },
    error: {
      light: '#FFE7E4',
      main: '#A02213',
    },
    warning: {
      light: '#FEF9D0',
      main: '#60590D',
    },
    success: {
      light: '#E7F4EC',
      main: '#18753C',
    },
    type: 'light',
  },
  typography: {
    fontFamily: 'Inter',
    h1: {
      fontSize: 36,
      lineHeight: '44px',
      fontWeight: 600,
    },
    h2: {
      fontSize: 28,
      lineHeight: '38px',
      fontWeight: 600,
    },
    h3: {
      fontSize: 24,
      lineHeight: '32px',
      fontWeight: 600,
    },
    h4: {
      fontSize: 20,
      lineHeight: '30px',
      fontWeight: 600,
    },
    h5: {
      fontSize: 18,
      lineHeight: '28px',
      fontWeight: 600,
    },
    h6: {
      fontSize: 16,
      lineHeight: '24px',
      fontWeight: 600,
    },
    body1: {
      fontSize: 14,
      lineHeight: '20px',
      fontWeight: 500,
    },
    body2: {
      fontSize: 14,
      lineHeight: '20px',
      fontWeight: 400,
    },
    caption: {
      fontSize: 12,
      lineHeight: '18px',
      fontWeight: 400,
    },
  },
})

export default regularTheme