(() => {
  const headers = document.querySelectorAll('[data-eden-wp-nav]')

  headers.forEach((header) => {
    const toggle = header.querySelector('[data-eden-wp-nav-toggle]')
    const panel = header.querySelector('[data-eden-wp-nav-panel]')
    const label = header.querySelector('[data-eden-wp-nav-label]')

    if (
      !(toggle instanceof HTMLButtonElement) ||
      !(panel instanceof HTMLElement) ||
      !(label instanceof HTMLElement)
    ) {
      return
    }

    const setOpen = (isOpen, restoreFocus = false) => {
      toggle.setAttribute('aria-expanded', String(isOpen))
      toggle.setAttribute('aria-label', isOpen ? 'Close primary navigation' : 'Open primary navigation')
      label.textContent = isOpen ? 'Close' : 'Menu'
      panel.classList.toggle('is-open', isOpen)

      if (restoreFocus) {
        toggle.focus()
      }
    }

    toggle.addEventListener('click', () => {
      setOpen(toggle.getAttribute('aria-expanded') !== 'true')
    })

    panel.addEventListener('click', (event) => {
      if (event.target instanceof HTMLAnchorElement) {
        setOpen(false)
      }
    })

    header.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
        setOpen(false, true)
      }
    })
  })
})()
