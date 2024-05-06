# Upgrading

## From v2 to v3

### `Sorting` interface (impact: low)

Adding sort functionality to a query now requires the sort class to implement the `Sorting` interface. 

If you're using or extending the default `Sort` class, no action is required. If you've created a custom sort class or overwritten any of the sort methods, you'll need to implement the `Sorting` interface.
