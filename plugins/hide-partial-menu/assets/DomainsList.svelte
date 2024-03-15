<script lang="ts">
  import { createEventDispatcher } from 'svelte'

  const { t } = globalThis.blessing

  type Item = {
    id: string
    value: string
    disabled: boolean
    translate: string
  }

  const dispath = createEventDispatcher()

  export let title = ''
  export let menutype = ''
  export let isDirty: boolean
  export let cardType = ''
  export let isLoading: boolean
  export let list: Item[] = []
</script>

<div class={`card card-${cardType}`}>
  <div class="card-header">
    <h3 class="card-title">
      {title}
      {#if isDirty}
        <span class="ml-1">●</span>
      {/if}
    </h3>
  </div>
  <div class="card-body">
    {#if isLoading}
      <div class="text-center">
        <i class="fas fa-sync fa-spin" />
      </div>
    {:else}
      {#each list as item (item.id)}
        <div class="form-group">
          <div class="custom-control custom-switch">
            <input
              type="checkbox"
              class="custom-control-input"
              id={item.id}
              bind:checked={item.disabled}
              on:change={() => {
                dispath('change', {
                  type: menutype,
                  value: item.value,
                  disabled: item.disabled,
                })
              }}
            />
            <label class="custom-control-label" for={item.id}
              >{item.translate}</label
            >
          </div>
        </div>
      {:else}
        <div class="text-center">
          <p>{t('general.noResult')}</p>
        </div>
      {/each}
    {/if}
  </div>
</div>
