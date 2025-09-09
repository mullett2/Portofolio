tailwind.config = {
  theme: {
    extend: {
      backgroundImage: {
        'about-bg': "url('assets/Aboutus2.jpg')",
        'land-bg': "url('assets/land.jpg')",
      },
      
      fontFamily: { 
        sans: ['Poppins', 'sans-serif'],
      },

      colors: {
        'primary-dark': '#1e283b',
        'primary-light': '#334155',
        'custom-accent': '#A2AF9B',
        'custom-eggshell' : '#FAF9EE',
        'custom-beige': '#DCCFC0',
        'custom-grey': '#EEEEEE',
      },

      keyframes: {
        'fade-in': {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
      },

      animation: {
        'fade-in': 'fade-in 0.8s ease-out forwards',
      },
    },
  },
};
