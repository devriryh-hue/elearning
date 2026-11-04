import { ref } from "vue";

export function useTest() {
  const test = ref("test");
  return {
    test,
  };
}