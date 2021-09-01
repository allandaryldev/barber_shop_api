import colors from 'vuetify/es5/util/colors'
const { join } = require("path");
const { copySync, removeSync } = require("fs-extra");

export default {
  // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
  ssr: false,

  srcDir: __dirname,

  target: 'static',

  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    titleTemplate: '%s - app',
    title: 'app',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', href: 'https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' },
    ],
    script: [
      {
        src: 'https://unpkg.com/boxicons@latest/dist/boxicons.js'
      }
    ]

  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '@/assets/bulma.scss',
  ],
  srcDir: 'client/',

  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
  plugins: [
    { src: "~/plugins/axios.js" },
    { src: "~/plugins/vee-validate.js" },
    { src: "~/plugins/vueqr.js" },
    '~/plugins/vuesax.client.js',
    '~/plugins/prime.client.js',
    { src: "~/plugins/v-money.js" },
    { src: "~/plugins/moneyformatter.js" },
  ],

  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,

  // Modules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
    // https://go.nuxtjs.dev/vuetify
    '@nuxtjs/vuetify',
    '@nuxtjs/tailwindcss',
    '@nuxtjs/moment',
    'nuxt-animejs',
    ['@nuxtjs/google-fonts', {
      families: {
        Roboto: true,
        Lato: true,
        Raleway: true,
        Montserrat: true
      }
    }],
  ],
  moment: {
    timezone: true,
    defaultTimezone: 'Asia/Manila'
  },

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    // '@nuxtjs/toast',cls
    ['nuxt-buefy', { css: false, ssr: false }],
    '@nuxtjs/auth-next',
    '@nuxtjs/toast',
    [
      'primevue/nuxt', {
        theme: 'md-light-deeppurple',
        ripple: true,
        components: ["Avatar", "Badge", "Breadcrumb", "Button", "Card",'Calendar',"Carousel", "Chart", "Checkbox", "ConfirmDialog", "ConfirmPopup",'Column', "Dialog", "Divider", 'DataTable','Dropdown',"Fieldset", "FileUpload", "Galleria", 'InputText','InputNumber',"Menu", "Menubar", "OverlayPanel", "ScrollTop", "SelectButton", "Skeleton", "TabPanel", "TabView", "Tag", "Timeline", "Toolbar",'Paginator'],
      }
    ],
  ],

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    credentials: true,
    // proxy: true,
    accept: 'application/json',
    baseURL: process.env.APP_URL,
  },
  loading: { name: 'chasing-dots', color: '#7e3af2', height: '3px' },
  auth: {
    // https://auth.nuxtjs.org/api/options.html
    redirect: {
      login: "/auth",
      logout: "/auth",
      callback: "/auth",
      home: false
    },
    // login: User will be redirected to this path if login is required.
    // logout: User will be redirected to this path if after logout, current route is protected.
    // home: User will be redirect to this path after login. (rewriteRedirects will rewrite this path)
    // callback: User will be redirected to this path by the identity provider after login. (Should match configured Allowed Callback URLs(or similar setting) in your app / client with the identity provider)
    // Each redirect path can be disabled by setting to false.Also you can disable all redirects by setting redirect to false
    // cookie:true,
    strategies: {
      'laravelSanctum': {
        provider: 'laravel/sanctum',
        url: process.env.APP_URL,
        endpoints: {
          csrf: {
            url: '/api/csrf-cookie', method: 'get',
          },
          login: { url: '/api/login', method: 'post' },
          user: { url: '/api/user', method: 'get' },
          logout: { url: '/api/logout', method: 'post' }

        },
        user: {
          property: 'data',
          autoFetch: true
        },
      },
    }
  },
  toast: {
    duration:3000,
    register: [ // Register custom toasts
      {
        name: 'center',
        message: message => message,
        options: {
          theme: 'none',
          duration: 1000,
          theme: 'none',
          position: 'bottom-center',
          className: 'rounded-md bg-purple-600 text-gray-200 hover:bg-purple-700',
          action: {
            text: 'Close',
            onClick: (e, toast) => {
              toast.goAway(0)
            }
          }
        }
      },
      {
        name: 'error',
        message: message => message,
        options: {
          theme: 'none',
          duration: 1000,
          theme: 'none',
          position: 'bottom-center',
          className: 'rounded-md bg-red-600 text-gray-200 hover:bg-red-700',
          action: {
            text: 'Close',
            onClick: (e, toast) => {
              toast.goAway(0)
            }
          }
        }
      }
    ]
  },
  pageTransition: {
    name: 'page',
    mode: 'out-in',
    css: false,

    beforeEnter(el) {
      this.$anime.set(el, {
        opacity: 0
      })
    },

    enter(el, done) {
      this.$anime({
        targets: el,
        opacity: [0, 1],
        duration: 500,
        easing: 'easeInOutSine',
        complete: done
      })
    },

    leave(el, done) {
      this.$anime({
        targets: el,
        opacity: [1, 0],
        duration: 500,
        easing: 'easeInOutSine',
        complete: done
      })
    }
  },
  router: {
    // middleware: ["auth"],
    extendRoutes(routes, resolve) {
      routes.push(
        {
          path: "/",
          redirect: "/app/transactions"
        },
        {
          path: "/auth",
          redirect: "/auth"
        },
      )
    }
  },
  // Vuetify module configuration: https://go.nuxtjs.dev/config-vuetify
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: false,
      themes: {
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3
        }
      }
    }
  },

  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  },
  hooks: {
    generate: {
      done(generator) {
        // Copy dist files to public/_nuxt
        const publicDir = join(generator.nuxt.options.rootDir, "public");
        // removeSync(publicDir);
        // copySync(
        //   join(generator.nuxt.options.generate.dir, "_nuxt"),
        //   publicDir
        // );
        copySync(join(generator.nuxt.options.generate.dir), publicDir);
        // copySync(
        //   join(generator.nuxt.options.generate.dir, "200.html"),
        //   join(publicDir, "index.html")
        // );
        // removeSync(generator.nuxt.opstions.generate.dir);
      }
    }
  }
}
