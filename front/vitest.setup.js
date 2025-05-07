import { expect, afterEach } from 'vitest'
import { cleanup } from '@testing-library/vue'
import '@testing-library/jest-dom'
import { config } from '@vue/test-utils'

// Automatically cleanup after each test
afterEach(() => {
  cleanup()
})

// Setup Vue Test Utils global config
config.global.stubs = {
  transition: false,
} 