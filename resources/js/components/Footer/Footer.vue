<template>
    <v-footer dark height="auto">
        <v-card
                class="flex"
                flat
                tile
        >
            <v-card-title class="secondary">
                <strong class="subheading">
                    {{ $t('message.socialConnect') }}
                </strong>

                <v-spacer></v-spacer>

                <v-btn
                        v-for="icon in icons"
                        :key="icon"
                        class="mx-3"
                        dark
                        icon
                >
                    <v-icon size="24px">{{ icon }}</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-actions class="justify-center">
                {{ $t('message.copyrightText') }}
            </v-card-actions>
        </v-card>
    </v-footer>
</template>

<script>
    export default {
        name: "Footer",

        data: () => ({
            icons: [
                'fab fa-facebook',
                'fab fa-twitter',
                //'fab fa-google-plus',
                'fab fa-linkedin',
                'fab fa-instagram'
            ]
        })
    }
</script>

<style scoped>

</style>