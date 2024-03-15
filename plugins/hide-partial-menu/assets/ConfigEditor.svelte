<script lang="ts">
  import { onMount } from 'svelte'
  import { nanoid } from 'nanoid'
  import DomainsList from './DomainsList.svelte'

  const { fetch, t, notify } = globalThis.blessing

  type Item = {
    id: string
    value: string
    disabled: boolean
    translate: string
  }
  type Items = Item[]

  let isLoading = true
  let isListDirty = false
  let lists: { [type: string]: Items } = {}

  onMount(async () => {
    const {
      menuData,
      disabledMenuData,
      translatesData,
    }: {
      menuData: Record<string, string[]>
      disabledMenuData: Record<string, string[]>
      translatesData: Record<string, Record<string, string>>
    } = await fetch.get('/admin/plugins/hide-partial-menu/api/getmenu')
    if (!menuData || !disabledMenuData || !translatesData) {
      throw new Error('Failed to fetch menu data')
    }

    for (const [type, items] of Object.entries<string[]>(menuData)) {
      const disabledItems = disabledMenuData[type] || []
      const translates = translatesData[type] || {}
      lists[type] = items.map((value: string) => ({
        id: nanoid(),
        value,
        disabled: disabledItems.includes(value),
        translate: translates[value] || value,
      }))
    }
    isLoading = false
  })

  async function changeList(event: {
    type: string
    value: string
    disabled: boolean
  }) {
    const url = `/admin/plugins/hide-partial-menu/api/changemenu`
    const resp = await fetch.post(url, event)
    if (resp === '') {
      notify.toast.success(t('hide-partial-menu.ok'))
      isListDirty = false
    }
  }
</script>

<div class="row">
  <div class="col-lg-6">
    {#each Object.keys(lists) as type}
      <DomainsList
        list={lists[type]}
        isDirty={isListDirty}
        menutype={type}
        title={t('hide-partial-menu.' + type + '.title')}
        cardType="primary"
        {isLoading}
        on:change={(event) => changeList(event.detail)}
      />
    {/each}
  </div>
</div>
