<template>
    <div class="search">
        <i class="bi bi-search icon" aria-hidden="true"></i>
        <input v-model="q" @input="search" type="search" name="q" :placeholder="this.searchTrans" required autocomplete="off"/>
        <ul class="search-dropdown" v-show="this.items.length">
            <li v-for="item in items" :key="item.id">
                <a :href="`${itemRoute}/${item.url}`">{{ item.title }}</a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            searchTrans: {
                type: String
            },
            searchRoute: {
                required: true,
                type: String
            },
            itemRoute: {
                required: true,
                type: String
            }
        },
        data: function() {
            return {
                q: '',
                items: []
            }
        },
        methods: {
            async search() {
                const response = await request.get(this.searchRoute.replace(':q', this.q))
                this.items = response.data.items
            },
        }
    }
</script>
