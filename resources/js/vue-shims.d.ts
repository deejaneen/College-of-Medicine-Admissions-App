declare module '*.vue' {
  import type { DefineComponent } from 'vue'
  
  // Use Record<string, unknown> for better type safety
  const component: DefineComponent<
    Record<string, unknown>, 
    Record<string, unknown>, 
    unknown,
    Record<string, unknown>,
    Record<string, unknown>
  >
  
  export default component
}