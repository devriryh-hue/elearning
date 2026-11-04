<script setup>

function emmissionfonction(textParent){
    return textParent
}
</script>
<template>
    <div class="flex">
        <h2>
            CECI EST LE TEST DE SLOT
        </h2>
        <slot></slot>
        <slot name="toast" :tust="'test de tust'"></slot>
        <slot name="fonct" :testfonctionslot="emmissionfonction"></slot>
    </div>
</template>