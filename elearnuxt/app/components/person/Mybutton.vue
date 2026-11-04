<template>
<button class="mybutt" >
    <slot></slot>
</button>
</template>
<style scoped>
.mybutt{
    color: white;
    border: none;
    padding: 5px 10px;
    margin: 5px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}
.mybutt:hover{
    background-color: #ecf011;
}
.mybutt a {
    text-decoration: none !important;
}
.mybutt.voir {
    background-color: var(--couleur-principale);
}
</style>