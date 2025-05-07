import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import TaskList from '../TaskList.vue'
import { createTestingPinia } from '@pinia/testing'
import PrimeVue from 'primevue/config'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import Tag from 'primevue/tag'
import Paginator from 'primevue/paginator'

describe('TaskList', () => {
  const createWrapper = (props = {}) => {
    return mount(TaskList, {
      global: {
        plugins: [
          createTestingPinia(),
          PrimeVue
        ],
        components: {
          DataTable,
          Column,
          Button,
          Tag,
          Paginator
        },
        directives: {
          tooltip: {
            mounted: () => {}
          }
        }
      },
      props: {
        tasks: [],
        totalRecords: 0,
        loading: false,
        sortField: null,
        sortOrder: null,
        ...props
      }
    })
  }

  it('renders properly with empty tasks', () => {
    const wrapper = createWrapper()
    expect(wrapper.exists()).toBe(true)
    expect(wrapper.find('.p-datatable').exists()).toBe(true)
  })

  it('renders tasks when provided', () => {
    const tasks = [
      {
        id: 1,
        title: 'Test Task',
        description: 'Test Description',
        status: 'new',
        status_color: 'blue'
      }
    ]
    
    const wrapper = createWrapper({
      tasks,
      totalRecords: 1
    })

    expect(wrapper.find('.p-datatable').exists()).toBe(true)
    expect(wrapper.text()).toContain('Test Task')
    expect(wrapper.text()).toContain('Test Description')
  })

  it('emits edit event when edit button is clicked', async () => {
    const tasks = [
      {
        id: 1,
        title: 'Test Task',
        description: 'Test Description',
        status: 'new',
        status_color: 'blue'
      }
    ]
    
    const wrapper = createWrapper({
      tasks,
      totalRecords: 1
    })

    const editButton = wrapper.find('.edit-button')
    expect(editButton.exists()).toBe(true)
    await editButton.trigger('click')
    expect(wrapper.emitted('edit')).toBeTruthy()
    expect(wrapper.emitted('edit')[0][0]).toEqual(tasks[0])
  })
}) 