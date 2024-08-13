{if isset($gifts) && count($gifts) > 0}
    <div class="gift-selection">
        <label for="gift-select">Selecciona tu regalo:</label>
        <select id="gift-select" name="selected_gift" class="form-control">
            {foreach from=$gifts item=gift}
                <option value="{$gift.id_product}">{$gift.gift_name}</option>
            {/foreach}
        </select>
    </div>
{/if}
