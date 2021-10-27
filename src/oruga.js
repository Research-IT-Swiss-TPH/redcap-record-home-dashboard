export default {
    iconPack: 'fas',
    button: {
      rootClass: 'btn',
      outlinedClass: 'btn-outline-',
      disabledClass: 'btn-disabled',
      variantClass: (variant, context) => { // Apply variant when the element is not outlined
        if (!context.props.outlined) {
          return `btn-${variant}`
        }
      }
    },
    select: {
      override: true,
      selectClass: 'custom-select',
      sizeClass: 'custom-select-'
    },
    notification: {
      duration: 3000,
      queue: true
    }
  }