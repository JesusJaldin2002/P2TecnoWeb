<template>
    <nav
        v-if="paginator !== undefined"
        class="flex items-center justify-between"
        role="navigation"
    >
        <div class="flex justify-between flex-1 sm:hidden">
            <span
                v-if="onFirstPage"
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 cursor-default leading-5 rounded-md"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        clip-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        fill-rule="evenodd"
                    />
                </svg>
            </span>
            <Link
                v-else
                :href="previousPageUrl"
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        clip-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        fill-rule="evenodd"
                    />
                </svg>
            </Link>

            <Link
                v-if="hasMorePages"
                :href="nextPageUrl"
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        clip-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        fill-rule="evenodd"
                    />
                </svg>
            </Link>
            <span
                v-else
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 cursor-default leading-5 rounded-md"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        clip-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        fill-rule="evenodd"
                    />
                </svg>
            </span>
        </div>

        <div
            class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
        >
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    Mostrando
                    <span class="font-medium" v-text="firstItem"></span>
                    a
                    <span class="font-medium" v-text="lastItem"></span>
                    de
                    <span class="font-medium" v-text="total"></span>
                    resultados
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    <span
                        v-if="onFirstPage"
                        aria-disabled="true"
                        aria-hidden="true"
                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 cursor-default rounded-l-md leading-5"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                clip-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                fill-rule="evenodd"
                            />
                        </svg>
                    </span>
                    <Link
                        v-else
                        :href="previousPageUrl"
                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
                        rel="prev"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                clip-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                fill-rule="evenodd"
                            />
                        </svg>
                    </Link>
                    <div v-for="(link, index) in paginator.links" :key="index">
                        <Link
                            v-if="
                                !isFirstOrLastOrDots(
                                    index,
                                    paginator.links.length,
                                    link.label
                                )
                            "
                            :class="{ 'bg-blue-200': link.active === true }"
                            :href="link.url"
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 hover:text-gray-500 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                            v-html="link.label"
                        ></Link>
                        <span
                            v-else-if="link.label === '...'"
                            aria-disabled="true"
                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5"
                        >
                            {{ link.label }}
                        </span>
                    </div>
                    <Link
                        v-if="hasMorePages"
                        :href="nextPageUrl"
                        class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md leading-5 hover:text-gray-400 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-500 transition ease-in-out duration-150"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                clip-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                fill-rule="evenodd"
                            />
                        </svg>
                    </Link>
                    <span
                        v-else
                        aria-disabled="true"
                        aria-hidden="true"
                        class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-gray-100 border border-gray-300 cursor-default rounded-r-md leading-5"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                clip-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                fill-rule="evenodd"
                            />
                        </svg>
                    </span>
                </span>
            </div>
        </div>
    </nav>
</template>

<script>
import { Link } from "@inertiajs/vue3";
import TermsOfService from "../Pages/TermsOfService.vue";

export default {
    name: "Paginator",
    components: { Link, TermsOfService },
    props: {
        paginator: {
            type: Object,
            required: true,
        },
    },
    methods: {
        isFirstOrLastOrDots(index, links_length, label) {
            return (
                index === 0 ||
                index === links_length - 1 ||
                label.includes("...")
            );
        },
    },
    computed: {
        onFirstPage() {
            return this.paginator.current_page === 1;
        },
        hasMorePages() {
            return this.paginator.current_page < this.paginator.last_page;
        },
        nextPageUrl() {
            return this.paginator.next_page_url;
        },
        previousPageUrl() {
            return this.paginator.prev_page_url;
        },
        firstItem() {
            return this.paginator.from;
        },
        lastItem() {
            return this.paginator.to;
        },
        total() {
            return this.paginator.total;
        },
    },
};
</script>
