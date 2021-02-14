<template>
    <li class="vote">
        <button @click="voted === 0 && vote(1)">
            <i v-bind:class="[voted === 1 ? 'bi bi-caret-up-fill' : 'bi bi-caret-up']"></i>
        </button>
        <span>{{ this.newSum }}</span>
        <button @click="voted === 0 && vote(-1)">
            <i v-bind:class="[voted === -1 ? 'bi bi-caret-down-fill' : 'bi bi-caret-down']"></i>
        </button>
    </li>
</template>

<script>
    import axios from 'axios'

    export default {
        props: {
            postId: {
                required: true,
                type: Number
            },
            voteRoute: {
                required: true,
                type: String
            },
            sum: {
                required: true,
                type: Number
            },
            isVoted: {
                required: true,
                type: Number
            },
            authenticated: {
                required: true,
                type: Boolean
            }
        },
        data: function() {
            return {
                newSum: this.sum,
                voted: this.isVoted,
                auth: this.authenticated
            }
        },
        methods: {
            async vote(vote) {
                if (this.auth){
                    const {data} = await axios.post(this.voteRoute, {post_id: this.postId, vote})
                    if (data.status){
                        this.newSum = Number(this.newSum) + Number(vote)
                        this.voted = vote
                    }
                }else{
                    this.register()
                }
            }
        }
    }
</script>
